<?php

namespace App\AuthBundle\Repository;

use App\AuthBundle\Entity\AccessToken;
use App\AuthBundle\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<AccessToken>
 *
 * @method AccessToken|null find($id, $lockMode = null, $lockVersion = null)
 * @method AccessToken|null findOneBy(array $criteria, array $orderBy = null)
 * @method AccessToken[]    findAll()
 * @method AccessToken[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AccessTokenRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, AccessToken::class);
    }

    /**
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function create(string $token, User $user): AccessToken
    {
        $accessTokenEntity = new AccessToken();
        $accessTokenEntity->setToken($token);
        $accessTokenEntity->setUser($user);

        $this->_em->persist($accessTokenEntity);

        $this->_em->flush();

        return $accessTokenEntity;
    }

    /**
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function remove(AccessToken $entity, bool $flush = true): void
    {
        $this->_em->remove($entity);
        if ($flush) {
            $this->_em->flush();
        }
    }

    public function deleteByUserId(int $userId)
    {
        $accessTokens = $this->findBy([
           'user' => $userId,
        ]);

        foreach ($accessTokens as $accessToken) {
            $this->_em->remove($accessToken);
        }

        $this->_em->flush();
    }

    // /**
    //  * @return AccessToken[] Returns an array of AccessToken objects
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
    public function findOneBySomeField($value): ?AccessToken
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
