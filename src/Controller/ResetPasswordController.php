<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\ResetPasswordRequestFormType;
use App\Form\ResetPasswordFormType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Address;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Csrf\TokenGenerator\TokenGeneratorInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

class ResetPasswordController extends AbstractController
{
    private $entityManager;
    private $tokenGenerator;
    private $mailer;
    private $urlGenerator;

    public function __construct(
        EntityManagerInterface $entityManager,
        TokenGeneratorInterface $tokenGenerator,
        MailerInterface $mailer,
        UrlGeneratorInterface $urlGenerator
    ) {
        $this->entityManager = $entityManager;
        $this->tokenGenerator = $tokenGenerator;
        $this->mailer = $mailer;
        $this->urlGenerator = $urlGenerator;
    }

    /**
     * @Route("/reset-password", name="app_forgot_password_request")
     */
    public function request(Request $request): Response
    {
        $form = $this->createForm(ResetPasswordRequestFormType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            return $this->processSendingPasswordResetEmail($form->get('email')->getData());
        }

        return $this->render('reset_password/request.html.twig', [
            'requestForm' => $form->createView(),
        ]);
    }

    /**
     * @Route("/reset-password/reset/{token}", name="app_reset_password")
     */
    public function reset(Request $request, UserPasswordHasherInterface $userPasswordHasher, string $token = null): Response
    {
        if ($token) {
            $this->storeTokenInSession($token);
            return $this->redirectToRoute('app_reset_password');
        }

        $token = $this->getTokenFromSession();
        if (null === $token) {
            throw $this->createNotFoundException('No reset password token found in the URL or in the session.');
        }

        try {
            $user = $this->entityManager->getRepository(User::class)->findOneBy(['resetToken' => $token]);
        } catch (\Exception $e) {
            $this->addFlash('reset_password_error', 'There was a problem validating your password reset request');
            return $this->redirectToRoute('app_forgot_password_request');
        }

        if (!$user) {
            $this->addFlash('reset_password_error', 'There was a problem validating your password reset request');
            return $this->redirectToRoute('app_forgot_password_request');
        }

        // Check if token has expired (24 hours)
        if ($user->getResetTokenExpiresAt() < new \DateTime()) {
            $this->addFlash('reset_password_error', 'Your password reset request has expired');
            return $this->redirectToRoute('app_forgot_password_request');
        }

        $form = $this->createForm(ResetPasswordFormType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // A password reset token should be used only once, remove it.
            $this->invalidateToken($user);

            // Encode the plain password, and set it.
            $encodedPassword = $userPasswordHasher->hashPassword(
                $user,
                $form->get('plainPassword')->getData()
            );

            $user->setPassword($encodedPassword);
            $this->entityManager->flush();

            // The session is cleaned up after the password has been changed.
            $this->cleanSessionAfterReset();

            $this->addFlash('success', 'Your password has been successfully reset');
            return $this->redirectToRoute('app_login');
        }

        return $this->render('reset_password/reset.html.twig', [
            'resetForm' => $form->createView(),
        ]);
    }

    private function processSendingPasswordResetEmail(string $emailFormData): Response
    {
        $user = $this->entityManager->getRepository(User::class)->findOneBy([
            'email' => $emailFormData,
        ]);

        // Do not reveal whether a user account was found or not.
        if (!$user) {
            return $this->redirectToRoute('app_check_email');
        }

        try {
            $resetToken = $this->tokenGenerator->generateToken();
        } catch (\Exception $e) {
            return $this->redirectToRoute('app_check_email');
        }

        $user->setResetToken($resetToken);
        $user->setResetTokenExpiresAt(new \DateTime('+1 hour'));
        $this->entityManager->flush();

        $email = (new TemplatedEmail())
            ->from(new Address('noreply@example.com', '2Why'))
            ->to($user->getEmail())
            ->subject('Your password reset request')
            ->htmlTemplate('reset_password/email.html.twig')
            ->context([
                'resetToken' => $resetToken,
                'tokenLifetime' => '1 hour',
            ]);

        $this->mailer->send($email);

        return $this->redirectToRoute('app_check_email');
    }

    /**
     * @Route("/reset-password/check-email", name="app_check_email")
     */
    public function checkEmail(): Response
    {
        return $this->render('reset_password/check_email.html.twig');
    }

    private function storeTokenInSession(string $token): void
    {
        $this->get('session')->set('ResetPasswordPublicToken', $token);
    }

    private function getTokenFromSession(): ?string
    {
        return $this->get('session')->get('ResetPasswordPublicToken');
    }

    private function invalidateToken(User $user): void
    {
        $user->setResetToken(null);
        $user->setResetTokenExpiresAt(null);
    }

    private function cleanSessionAfterReset(): void
    {
        $this->get('session')->remove('ResetPasswordPublicToken');
    }
}
