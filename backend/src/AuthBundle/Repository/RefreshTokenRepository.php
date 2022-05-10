<?php

namespace App\AuthBundle\Repository;

use App\AuthBundle\Entity\RefreshToken;
use App\AuthBundle\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<RefreshToken>
 *
 * @method RefreshToken|null find($id, $lockMode = null, $lockVersion = null)
 * @method RefreshToken|null findOneBy(array $criteria, array $orderBy = null)
 * @method RefreshToken[]    findAll()
 * @method RefreshToken[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class RefreshTokenRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, RefreshToken::class);
    }

    /**
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function create(string $token, User $user): RefreshToken
    {
        $refreshTokenEntity = new RefreshToken();
        $refreshTokenEntity->setToken($token);
        $refreshTokenEntity->setUser($user);

        $this->_em->persist($refreshTokenEntity);

        $this->_em->flush();

        return $refreshTokenEntity;
    }

    /**
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function remove(RefreshToken $entity, bool $flush = true): void
    {
        $this->_em->remove($entity);
        if ($flush) {
            $this->_em->flush();
        }
    }

    public function deleteByUserId(int $userId)
    {
        $refreshTokens = $this->findBy([
            'user' => $userId,
        ]);

        foreach ($refreshTokens as $refreshToken) {
            $this->_em->remove($refreshToken);
        }

        $this->_em->flush();
    }

    // /**
    //  * @return RefreshToken[] Returns an array of RefreshToken objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('a.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?RefreshToken
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
