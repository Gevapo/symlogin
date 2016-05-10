<?php
/**
 * Created by PhpStorm.
 * User: Geert
 * Date: 28/04/2016
 * Time: 11:44
 */

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\AdvancedUserInterface;

/**
 * @ORM\Entity(repositoryClass="AppBundle\Entity\UserRepository")
 * @UniqueEntity(fields="email", message="Email already taken")
 * @UniqueEntity(fields="username", message="Username already taken")
 * @ORM\Table(name="app_users")
 * @ORM\HasLifecycleCallbacks()
 */
class User implements AdvancedUserInterface, \Serializable
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255, unique=true)
     * @Assert\NotBlank()
     * @Assert\Email()
     */
    private $email;

    /**
     * @ORM\Column(type="string", length=25, unique=true)
     * @Assert\NotBlank()
     */
    private $username;

    /**
     * @Assert\NotBlank()
     * @Assert\Length(max=4096)
     */
    private $plainPassword;

    /**
     * The below length depends on the "algorithm" you use for encoding
     * the password, but this works well with bcrypt.
     *
     * @ORM\Column(type="string", length=64)
     */
    private $password;

    /**
     * @ORM\Column(name="is_active", type="boolean")
     */
    private $isActive;

    /**
     * @ORM\Column(name="roles", type="string", length=64)
     */
    private $roles;

    /**
     * @ORM\Column(name="active_hash", type="string", length=255, nullable=true)
     */
    private $activeHash;

    /**
     * @ORM\Column(name="recover_hash", type="string", length=255, nullable=true)
     */
    private $recoverHash;

    /**
     * @ORM\Column(name="remember_identifier", type="string", length=255, nullable=true)
     */
    private $rememberIdentifier;

    /**
     * @ORM\Column(name="remember_token", type="string", length=255, nullable=true)
     */
    private $rememberToken;

    /**
     * @ORM\Column(name="created_at", type="datetime")
     */
    private $createdAt;

    /**
     * @ORM\Column(name="updated_at", type="datetime")
     */
    private $updatedAt;

    public function __construct()
    {
        $this->isActive = true;
        // may not be needed, see section on salt below
        // $this->salt = md5(uniqid(null, true));
        $this->setCreatedAt(new \DateTime());
        $this->setUpdatedAt(new \DateTime());
    }


    public function setUpdatedValue()
    {
        $this->setUpdatedAt(new \DateTime());
    }

    public function isAccountNonExpired()
    {
        return true;
    }

    public function isAccountNonLocked()
    {
        return true;
    }

    public function isCredentialsNonExpired()
    {
        return true;
    }

    public function isEnabled()
    {
        return $this->isActive;
    }

    public function getPassword()
    {
        return $this->password;
    }

    public function getRoles()
    {
        //return array('ROLE_USER');
        //return array('ROLE_ADMIN');
        return array($this->roles);
    }

    public function eraseCredentials()
    {
    }

    /** @see \Serializable::serialize() */
    public function serialize()
    {
        return serialize(array(
            $this->id,
            $this->username,
            $this->password,
            $this->isActive,
            // see section on salt below
            // $this->salt,
        ));
    }

    /** @see \Serializable::unserialize() */
    public function unserialize($serialized)
    {
        list (
            $this->id,
            $this->username,
            $this->password,
            $this->isActive
            // see section on salt below
            // $this->salt
            ) = unserialize($serialized);
    }

    public function getEmail()
    {
        return $this->email;
    }

    public function setEmail($email)
    {
        $this->email = $email;
    }

    public function getUsername()
    {
        return $this->username;
    }

    public function setUsername($username)
    {
        $this->username = $username;
    }

    public function getPlainPassword()
    {
        return $this->plainPassword;
    }

    public function setPlainPassword($password)
    {
        $this->plainPassword = $password;
    }

    public function setPassword($password)
    {
        $this->password = $password;
    }

    public function getSalt()
    {
        // The bcrypt algorithm doesn't require a separate salt.
        // You *may* need a real salt if you choose a different encoder.
        return null;
    }

    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set isActive
     *
     * @param boolean $isActive
     *
     * @return User
     */
    public function setIsActive($isActive)
    {
        $this->isActive = $isActive;

        return $this;
    }

    /**
     * Get isActive
     *
     * @return boolean
     */
    public function getIsActive()
    {
        return $this->isActive;
    }

    /**
     * Set roles
     *
     * @param string $roles
     *
     * @return User
     */
    public function setRoles($roles)
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * Set activeHash
     *
     * @param string $activeHash
     *
     * @return User
     */
    public function setActiveHash($activeHash)
    {
        $this->activeHash = $activeHash;

        return $this;
    }

    /**
     * Get activeHash
     *
     * @return string
     */
    public function getActiveHash()
    {
        return $this->activeHash;
    }

    /**
     * Set recoverHash
     *
     * @param string $recoverHash
     *
     * @return User
     */
    public function setRecoverHash($recoverHash)
    {
        $this->recoverHash = $recoverHash;

        return $this;
    }

    /**
     * Get recoverHash
     *
     * @return string
     */
    public function getRecoverHash()
    {
        return $this->recoverHash;
    }

    /**
     * Set rememberIdentifier
     *
     * @param string $rememberIdentifier
     *
     * @return User
     */
    public function setRememberIdentifier($rememberIdentifier)
    {
        $this->rememberIdentifier = $rememberIdentifier;

        return $this;
    }

    /**
     * Get rememberIdentifier
     *
     * @return string
     */
    public function getRememberIdentifier()
    {
        return $this->rememberIdentifier;
    }

    /**
     * Set rememberToken
     *
     * @param string $rememberToken
     *
     * @return User
     */
    public function setRememberToken($rememberToken)
    {
        $this->rememberToken = $rememberToken;

        return $this;
    }

    /**
     * Get rememberToken
     *
     * @return string
     */
    public function getRememberToken()
    {
        return $this->rememberToken;
    }

    /**
     * Set createdAt
     *
     * @param \DateTime $createdAt
     *
     * @return User
     */
    public function setCreatedAt($createdAt)
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    /**
     * Get createdAt
     *
     * @return \DateTime
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * Set updatedAt
     *
     * @param \DateTime $updatedAt
     *
     * @return User
     */
    public function setUpdatedAt($updatedAt)
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    /**
     * Get updatedAt
     *
     * @return \DateTime
     */
    public function getUpdatedAt()
    {
        return $this->updatedAt;
    }

    public function getAvatarUrl($options = [])
    {
        $size = isset($options['size']) ? $options['size'] : 45;
        return 'http://www.gravatar.com/avatar/' . md5($this->getEmail()) . '?s=' . $size . '&d=mm';
    }
}
