<?php
namespace Dizda\Bundle\UserBundle\Entity;

use Dizda\Bundle\AppBundle\Traits\Timestampable;
use FOS\UserBundle\Model\User as BaseUser;
use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as JMS;

/**
 * @ORM\Entity
 * @ORM\Table(name="fos_user")
 * @ORM\HasLifecycleCallbacks
 */
class User extends BaseUser
{
    use Timestampable;

    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     * @JMS\Exclude()
     */
    protected $id;

    public function __construct()
    {
        parent::__construct();
        // your own logic
    }
}
