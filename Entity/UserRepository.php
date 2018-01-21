<?php 
namespace DB\UserBundle\Entity;

use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Core\Exception\UsernameNotFoundException;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;

use Doctrine\ORM\EntityRepository;

class UserRepository extends EntityRepository implements UserProviderInterface
{
     public function my_findBy($table, $orderby = null, $limit = null, $offset = null) {
        $param = array();
        $txt = "(";
        $queryBuilder = $this->createQueryBuilder('a')
                ->select('a');
        $j = 0;
        if (sizeof($table) > 0) {
            foreach ($table as $key => $value) {
                if ($j > 0) {
                    $txt .= " AND ( ";
                }
                if (is_array($value)) {
                    for ($i = 0; $i < sizeof($value); $i++) {
                        switch ($key) {
                            case "created":
                            case "updated":
                            case "dateOrder":
                                if ($i == 0)
                                    $txt .= 'a.' . $key . ' >= :' . $key . $i;
                                else
                                    $txt .= ' OR a.' . $key . ' >= :' . $key . $i;
                                $tmp = \DateTime::createFromFormat('d/m/Y', $value[$i]);
                                if ($tmp) {
                                    $value[$i] = $tmp->format('Y-m-d');
                                }
                                break;
                            case "dateEndActivity" :
                            case "dateBeginActivity" :
                                if ($i == 0)
                                    $txt .= 'a.' . $key . ' = :' . $key . $i;
                                else
                                    $txt .= ' OR a.' . $key . ' = :' . $key . $i;
                                $tmp = \DateTime::createFromFormat('d/m/Y', $value[$i]);
                                if ($tmp) {
                                    $value[$i] = $tmp->format('Y-m-d');
                                }
                                break;
                            case (preg_match('/^r_/', $key) ? true : false):
                                substr($key, 0, 6);
                                if ($i == 0)
                                    $txt .= ' REGEXP(a.' . substr($key, 2, strlen($key)) . ', :' . $key . $i . ') = 1';
                                else
                                    $txt .= ' OR REGEXP(a.' . substr($key, 2, strlen($key)) . ', :' . $key . $i . ') = 1';
                                break;
                            default :
                                if ($i == 0)
                                    $txt .= 'a.' . $key . ' = :' . $key . $i;
                                else
                                    $txt .= ' OR a.' . $key . ' = :' . $key . $i;
                                break;
                        }
                        $queryBuilder->setParameter($key . $i, $value[$i]);
                    }
                    $j++;
                    $txt .= ")";
                } else {
                    switch ($key) {
                        case "created":
                        case "updated":
                        case "dateOrder":
                            $txt .= 'a.' . $key . ' >= :' . $key;
                            $tmp = \DateTime::createFromFormat('d/m/Y', $value);
                            if ($tmp) {
                                $value = $tmp->format('Y-m-d');
                            }
                            break;
                        case (preg_match('/^r_/', $key) ? true : false):
                            substr($key, 0, 6);
                            $txt .= 'REGEXP(a.' . substr($key, 2, strlen($key)) . ', :' . $key . ') = 1';
                            break;
                        default :
                            $txt .= 'a.' . $key . ' = :' . $key;
                            break;
                    }
                    $queryBuilder->setParameter($key, $value);
                    $j++;
                    $txt .= ")";
                }
            }
            $queryBuilder->where($txt);
        }

        foreach ((array) $orderby as $key => $value) {
            $queryBuilder->orderBy("a." . $key, $value);
        }
        return $queryBuilder->getQuery()
                        ->setMaxResults($limit)
                        ->setFirstResult($offset)
                        ->getResult();
    }
    
    public function loadUserByUsername($username)
    {
        $q = $this->createQueryBuilder('u')
            ->where('u.username = :username OR u.email = :email')
            ->setParameter('username', $username)
            ->setParameter('email', $username)
            ->getQuery();
        
        try {
            // La méthode Query::getSingleResult() lance une exception
            // s'il n'y a pas d'entrée correspondante aux critères
            $user = $q->getSingleResult();
        } catch (NoResultException $e) {
            throw new UsernameNotFoundException(sprintf('Unable to find an active admin DBManagerBundle:User object identified by "%s".', $username), 0, $e);
        }

        return $user;
    }

    public function refreshUser(UserInterface $user)
    {
        $class = get_class($user);
        if (!$this->supportsClass($class)) {
            throw new UnsupportedUserException(
                sprintf(
                    'Instances of "%s" are not supported.',
                    $class
                )
            );
        }

        return $this->find($user->getId());
    }

    public function supportsClass($class)
    {
        return $this->getEntityName() === $class
            || is_subclass_of($class, $this->getEntityName());
    }
}
