<?php

namespace AppBundle\Services;

use AppBundle\Entity\Account;
use AppBundle\Entity\Transaction;
use Doctrine\ORM\EntityRepository;

class ActionService extends EntityRepository
{
    public function get(int $page = 1, int $limit = 30)
    {
        return $this->getEntityManager()->createQuery(<<<DQL
SELECT a, att
FROM AppBundle\Entity\Action a
JOIN a.transaction att
WHERE a.parent IS NULL
ORDER BY a.id DESC
DQL
        )
            ->setFirstResult($limit * ($page - 1))
            ->setMaxResults($limit)
            ->getResult();
    }

    public function getFromAccount(Account $account, int $page = 1, int $limit = 30)
    {
        return $this->getEntityManager()->createQuery(<<<DQL
SELECT a, aa, att
FROM AppBundle\Entity\Action a
LEFT JOIN a.authorizations aa
JOIN a.transaction att
WHERE aa.actor = :ACCOUNT
ORDER BY a.id DESC
DQL
        )
            ->setParameter('ACCOUNT', $account->name())
            ->setFirstResult($limit * ($page - 1))
            ->setMaxResults($limit)
            ->getResult();
    }

    public function countLast(int $hours): int
    {
        $datetime = new \DateTime('-'.$hours.' hours');
        $sql = "SELECT count(a.id) as cant FROM actions a JOIN transactions t ON a.transaction_id = t.id WHERE a.parent_id = 0 AND t.created_at > '".date_format($datetime, 'Y-m-d H:i:s')."' LIMIT 1";
        $stmt = $this->getEntityManager()->getConnection()->prepare($sql);
        $stmt->execute();
        $result = $stmt->fetchAll();
        return ($result) ? $result[0]['cant']: 0;

    }

    public function getToAccount(Account $account, int $page = 1, int $limit = 30)
    {
        return $this->getEntityManager()->createQuery(<<<DQL
SELECT a, aa, att
FROM AppBundle\Entity\Action a
LEFT JOIN a.authorizations aa
JOIN a.transaction att
WHERE a.account = :ACCOUNT
ORDER BY a.id DESC
DQL
        )
            ->setParameter('ACCOUNT', $account->name())
            ->setFirstResult($limit * ($page - 1))
            ->setMaxResults($limit)
            ->getResult();
    }

    public function getForTransaction(Transaction $transaction, int $page = 1, int $limit = 30)
    {
        return $this->getEntityManager()->createQuery(<<<DQL
SELECT a, aa, att
FROM AppBundle\Entity\Action a
LEFT JOIN a.authorizations aa
JOIN a.transaction att
WHERE a.transaction = :TRANSACTION
ORDER BY a.id ASC
DQL
        )
            ->setParameter('TRANSACTION', $transaction)
            ->setFirstResult($limit * ($page - 1))
            ->setMaxResults($limit)
            ->getResult();
    }
}
