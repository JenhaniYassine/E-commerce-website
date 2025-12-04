<?php

namespace App\Repository;

use App\Entity\Product;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Product>
 *
 * @method Product|null find($id, $lockMode = null, $lockVersion = null)
 * @method Product|null findOneBy(array $criteria, array $orderBy = null)
 * @method Product[]    findAll()
 * @method Product[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ProductRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Product::class);
    }

    public function add(Product $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Product $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

//    /**
//     * @return Product[] Returns an array of Product objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('p')
//            ->andWhere('p.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('p.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Product
//    {
//        return $this->createQueryBuilder('p')
//            ->andWhere('p.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }

    /**
     * @return Product[] Returns an array of Product objects for new arrivals
     */
    public function findNewArrivals(int $limit = 8): array
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.status = :status')
            ->setParameter('status', true)
            ->orderBy('p.createdAt', 'DESC')
            ->setMaxResults($limit)
            ->getQuery()
            ->getResult()
        ;
    }

    /**
     * @return Product[] Returns an array of Product objects for best sellers (simulated by stock quantity)
     */
    public function findBestSellers(int $limit = 8): array
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.status = :status')
            ->setParameter('status', true)
            ->orderBy('p.stockQuantity', 'DESC')
            ->setMaxResults($limit)
            ->getQuery()
            ->getResult()
        ;
    }

    /**
     * @return array Returns an array of Product objects with filters, sorting, and pagination
     */
    public function findByFilters(array $filters = [], string $sort = 'name', string $order = 'ASC', int $page = 1, int $limit = 12): array
    {
        $qb = $this->createQueryBuilder('p')
            ->andWhere('p.status = :status')
            ->setParameter('status', true);

        // Category filter
        if (!empty($filters['category'])) {
            $qb->andWhere('p.category = :category')
               ->setParameter('category', $filters['category']);
        }

        // Price range filter
        if (!empty($filters['minPrice'])) {
            $qb->andWhere('p.price >= :minPrice')
               ->setParameter('minPrice', $filters['minPrice']);
        }
        if (!empty($filters['maxPrice'])) {
            $qb->andWhere('p.price <= :maxPrice')
               ->setParameter('maxPrice', $filters['maxPrice']);
        }

        // Brand filter (if added later)
        if (!empty($filters['brand'])) {
            $qb->andWhere('p.brand = :brand')
               ->setParameter('brand', $filters['brand']);
        }

        // Sorting
        switch ($sort) {
            case 'price':
                $qb->orderBy('p.price', $order);
                break;
            case 'popularity':
                $qb->orderBy('p.stockQuantity', $order); // Using stock as popularity proxy
                break;
            case 'rating':
                $qb->orderBy('p.rating', $order);
                break;
            case 'newest':
                $qb->orderBy('p.createdAt', 'DESC');
                break;
            default:
                $qb->orderBy('p.name', $order);
        }

        // Pagination
        $qb->setFirstResult(($page - 1) * $limit)
           ->setMaxResults($limit);

        return $qb->getQuery()->getResult();
    }

    /**
     * @return int Returns the total count of products matching filters
     */
    public function countByFilters(array $filters = []): int
    {
        $qb = $this->createQueryBuilder('p')
            ->select('COUNT(p.id)')
            ->andWhere('p.status = :status')
            ->setParameter('status', true);

        // Category filter
        if (!empty($filters['category'])) {
            $qb->andWhere('p.category = :category')
               ->setParameter('category', $filters['category']);
        }

        // Price range filter
        if (!empty($filters['minPrice'])) {
            $qb->andWhere('p.price >= :minPrice')
               ->setParameter('minPrice', $filters['minPrice']);
        }
        if (!empty($filters['maxPrice'])) {
            $qb->andWhere('p.price <= :maxPrice')
               ->setParameter('maxPrice', $filters['maxPrice']);
        }

        // Brand filter (if added later)
        if (!empty($filters['brand'])) {
            $qb->andWhere('p.brand = :brand')
               ->setParameter('brand', $filters['brand']);
        }

        return $qb->getQuery()->getSingleScalarResult();
    }

    /**
     * @return Product[] Returns an array of Product objects matching search query
     */
    public function searchByQuery(string $query, array $filters = [], string $sort = 'name', string $order = 'ASC', int $page = 1, int $limit = 12): array
    {
        $qb = $this->createQueryBuilder('p')
            ->andWhere('p.status = :status')
            ->setParameter('status', true)
            ->andWhere('(p.name LIKE :query OR p.description LIKE :query)')
            ->setParameter('query', '%' . $query . '%');

        // Category filter
        if (!empty($filters['category'])) {
            $qb->andWhere('p.category = :category')
               ->setParameter('category', $filters['category']);
        }

        // Price range filter
        if (!empty($filters['minPrice'])) {
            $qb->andWhere('p.price >= :minPrice')
               ->setParameter('minPrice', $filters['minPrice']);
        }
        if (!empty($filters['maxPrice'])) {
            $qb->andWhere('p.price <= :maxPrice')
               ->setParameter('maxPrice', $filters['maxPrice']);
        }

        // Sorting
        switch ($sort) {
            case 'price':
                $qb->orderBy('p.price', $order);
                break;
            case 'popularity':
                $qb->orderBy('p.stockQuantity', $order); // Using stock as popularity proxy
                break;
            case 'rating':
                $qb->orderBy('p.rating', $order);
                break;
            case 'newest':
                $qb->orderBy('p.createdAt', 'DESC');
                break;
            default:
                $qb->orderBy('p.name', $order);
        }

        // Pagination
        $qb->setFirstResult(($page - 1) * $limit)
           ->setMaxResults($limit);

        return $qb->getQuery()->getResult();
    }

    /**
     * @return int Returns the total count of products matching search query and filters
     */
    public function countBySearchQuery(string $query, array $filters = []): int
    {
        $qb = $this->createQueryBuilder('p')
            ->select('COUNT(p.id)')
            ->andWhere('p.status = :status')
            ->setParameter('status', true)
            ->andWhere('(p.name LIKE :query OR p.description LIKE :query)')
            ->setParameter('query', '%' . $query . '%');

        // Category filter
        if (!empty($filters['category'])) {
            $qb->andWhere('p.category = :category')
               ->setParameter('category', $filters['category']);
        }

        // Price range filter
        if (!empty($filters['minPrice'])) {
            $qb->andWhere('p.price >= :minPrice')
               ->setParameter('minPrice', $filters['minPrice']);
        }
        if (!empty($filters['maxPrice'])) {
            $qb->andWhere('p.price <= :maxPrice')
               ->setParameter('maxPrice', $filters['maxPrice']);
        }

        return $qb->getQuery()->getSingleScalarResult();
    }

    /**
     * @return Product[] Returns an array of Product objects for autocomplete suggestions
     */
    public function findAutocompleteSuggestions(string $query, int $limit = 10): array
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.status = :status')
            ->setParameter('status', true)
            ->andWhere('p.name LIKE :query')
            ->setParameter('query', $query . '%')
            ->orderBy('p.name', 'ASC')
            ->setMaxResults($limit)
            ->getQuery()
            ->getResult();
    }

    /**
     * @return Product[] Returns an array of Product objects recently viewed by user
     */
    public function findRecentlyViewed(array $ids, int $limit = 8): array
    {
        if (empty($ids)) {
            return [];
        }

        $products = $this->createQueryBuilder('p')
            ->andWhere('p.status = :status')
            ->andWhere('p.id IN (:ids)')
            ->setParameter('status', true)
            ->setParameter('ids', $ids)
            ->getQuery()
            ->getResult();

        // Sort products to match the order of IDs
        $idToProduct = [];
        foreach ($products as $product) {
            $idToProduct[$product->getId()] = $product;
        }

        $sortedProducts = [];
        foreach ($ids as $id) {
            if (isset($idToProduct[$id])) {
                $sortedProducts[] = $idToProduct[$id];
                if (count($sortedProducts) >= $limit) {
                    break;
                }
            }
        }

        return $sortedProducts;
    }

    /**
     * @return Product[] Returns an array of Product objects as recommendations (simplified random selection)
     */
    public function findRecommendations(int $userId, int $limit = 8): array
    {
        $products = $this->createQueryBuilder('p')
            ->andWhere('p.status = :status')
            ->setParameter('status', true)
            ->getQuery()
            ->getResult();

        // Shuffle the array to randomize
        shuffle($products);

        return array_slice($products, 0, $limit);
    }

    /**
     * @return Product[] Returns an array of Product objects trending (simplified by rating)
     */
    public function findTrending(int $limit = 8): array
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.status = :status')
            ->setParameter('status', true)
            ->orderBy('p.rating', 'DESC')
            ->setMaxResults($limit)
            ->getQuery()
            ->getResult();
    }

    public function getUserCategoryPreferences(int $userId): array
    {
        $qb = $this->createQueryBuilder('p')
            ->select('c.name as categoryName, COUNT(oi.id) as orderCount, SUM(oi.quantity) as totalQuantity')
            ->join('p.category', 'c')
            ->join('p.orderItems', 'oi')
            ->join('oi.order', 'o')
            ->where('o.user = :userId')
            ->setParameter('userId', $userId)
            ->groupBy('c.id, c.name')
            ->orderBy('totalQuantity', 'DESC');

        return $qb->getQuery()->getResult();
    }

    public function getUserTopProducts(int $userId, int $limit = 5): array
    {
        $qb = $this->createQueryBuilder('p')
            ->select('p.name, p.price, SUM(oi.quantity) as totalQuantity, COUNT(DISTINCT o.id) as orderCount')
            ->join('p.orderItems', 'oi')
            ->join('oi.order', 'o')
            ->where('o.user = :userId')
            ->setParameter('userId', $userId)
            ->groupBy('p.id, p.name, p.price')
            ->orderBy('totalQuantity', 'DESC')
            ->setMaxResults($limit);

        return $qb->getQuery()->getResult();
    }
}
