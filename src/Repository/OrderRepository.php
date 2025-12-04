<?php

namespace App\Repository;

use App\Entity\Order;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Order>
 *
 * @method Order|null find($id, $lockMode = null, $lockVersion = null)
 * @method Order|null findOneBy(array $criteria, array $orderBy = null)
 * @method Order[]    findAll()
 * @method Order[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class OrderRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Order::class);
    }

    public function findByStatus(string $status): array
    {
        return $this->createQueryBuilder('o')
            ->andWhere('o.status = :status')
            ->setParameter('status', $status)
            ->orderBy('o.createdAt', 'DESC')
            ->getQuery()
            ->getResult();
    }

    public function findAllOrderedByDate(): array
    {
        return $this->createQueryBuilder('o')
            ->orderBy('o.createdAt', 'DESC')
            ->getQuery()
            ->getResult();
    }

    public function findPendingOrders(): array
    {
        return $this->findByStatus('Pending');
    }

    public function findConfirmedOrders(): array
    {
        return $this->findByStatus('Confirmed');
    }

    public function findShippedOrders(): array
    {
        return $this->findByStatus('Shipped');
    }

    public function findDeliveredOrders(): array
    {
        return $this->findByStatus('Delivered');
    }

    public function findCancelledOrders(): array
    {
        return $this->findByStatus('Cancelled');
    }

    public function getUserOrderStats(int $userId): array
    {
        $qb = $this->createQueryBuilder('o')
            ->select('COUNT(o.id) as totalOrders, SUM(o.total) as totalSpent, AVG(o.total) as avgOrderValue')
            ->where('o.user = :userId')
            ->setParameter('userId', $userId);

        return $qb->getQuery()->getSingleResult();
    }

    public function getUserMonthlySpending(int $userId, int $months = 12): array
    {
        $startDate = new \DateTime("-$months months");

        $sql = 'SELECT YEAR(o.created_at) as year, MONTH(o.created_at) as month, SUM(o.total) as total
                FROM `order` o
                WHERE o.user_id = :userId AND o.created_at >= :startDate
                GROUP BY year, month
                ORDER BY year ASC, month ASC';

        $result = $this->getEntityManager()->getConnection()->executeQuery($sql, [
            'userId' => $userId,
            'startDate' => $startDate->format('Y-m-d H:i:s')
        ]);

        return $result->fetchAllAssociative();
    }

    public function getUserOrderFrequency(int $userId): array
    {
        $startDate = new \DateTime('-30 days');

        $sql = 'SELECT COUNT(o.id) as orderCount, DATE(o.created_at) as orderDate
                FROM `order` o
                WHERE o.user_id = :userId AND o.created_at >= :startDate
                GROUP BY orderDate
                ORDER BY orderDate ASC';

        $result = $this->getEntityManager()->getConnection()->executeQuery($sql, [
            'userId' => $userId,
            'startDate' => $startDate->format('Y-m-d H:i:s')
        ]);

        return $result->fetchAllAssociative();
    }

    //    /**
    //     * @return Order[] Returns an array of Order objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('o')
    //            ->andWhere('o.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('o.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?Order
    //    {
    //        return $this->createQueryBuilder('o')
    //            ->andWhere('o.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
