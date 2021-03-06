<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass=UserRepository::class)
 * @UniqueEntity(fields={"email"}, message="There is already an account with this email")
 */
class User implements UserInterface
{
    /**
     * @Groups("comment")
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=180, unique=true)
     */
    private $email;

    /**
     * @ORM\Column(type="json")
     */
    private $roles = [];

    /**
     * @var string The hashed password
     * @ORM\Column(type="string")
     */
    private $password;

    /**
     * @Groups("comment")
     * @ORM\Column(type="string", length=50)
     */
    private $name;

    /**
     * @ORM\Column(type="datetime")
     */
    private $created_at;

    /**
     * @ORM\Column(type="boolean")
     */
    private $isVerified = false;

    /**
     * @ORM\OneToMany(targetEntity=Resource::class, mappedBy="user", orphanRemoval=true)
     */
    private $resources;

    /**
     * @ORM\OneToMany(targetEntity=Reaction::class, mappedBy="user", orphanRemoval=true)
     */
    private $reactions;

    /**
     * @ORM\OneToMany(targetEntity=Comment::class, mappedBy="author", orphanRemoval=true)
     */
    private $comments;

    /**
     * @ORM\OneToMany(targetEntity=SubscribeResource::class, mappedBy="user", orphanRemoval=true)
     */
    private $subscribeResources;

    /**
     * @ORM\OneToMany(targetEntity=SubscribeUser::class, mappedBy="user", orphanRemoval=true)
     */
    private $subscribeUsers;

    /**
     * @ORM\OneToMany(targetEntity=SubscribeUser::class, mappedBy="subscribed", orphanRemoval=true)
     */
    private $subscribedUsers;

    public function __construct()
    {
        $this->created_at = new \DateTime();
        $this->resources = new ArrayCollection();
        $this->reactions = new ArrayCollection();
        $this->comments = new ArrayCollection();
        $this->subscribeResources = new ArrayCollection();
        $this->subscribeUsers = new ArrayCollection();
        $this->subscribedUsers = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUsername(): string
    {
        return (string) $this->email;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function getPassword(): string
    {
        return (string) $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * Returning a salt is only needed, if you are not using a modern
     * hashing algorithm (e.g. bcrypt or sodium) in your security.yaml.
     *
     * @see UserInterface
     */
    public function getSalt(): ?string
    {
        return null;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->created_at;
    }

    public function setCreatedAt(\DateTimeInterface $created_at): self
    {
        $this->created_at = $created_at;

        return $this;
    }

    public function isVerified(): bool
    {
        return $this->isVerified;
    }

    public function setIsVerified(bool $isVerified): self
    {
        $this->isVerified = $isVerified;

        return $this;
    }

    /**
     * @return Collection|Resource[]
     */
    public function getResources(): Collection
    {
        return $this->resources;
    }

    public function addResource(Resource $resource): self
    {
        if (!$this->resources->contains($resource)) {
            $this->resources[] = $resource;
            $resource->setUser($this);
        }

        return $this;
    }

    public function removeResource(Resource $resource): self
    {
        if ($this->resources->removeElement($resource)) {
            // set the owning side to null (unless already changed)
            if ($resource->getUser() === $this) {
                $resource->setUser(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Reaction[]
     */
    public function getReactions(): Collection
    {
        return $this->reactions;
    }

    public function addReaction(Reaction $reaction): self
    {
        if (!$this->reactions->contains($reaction)) {
            $this->reactions[] = $reaction;
            $reaction->setUser($this);
        }

        return $this;
    }

    public function removeReaction(Reaction $reaction): self
    {
        if ($this->reactions->removeElement($reaction)) {
            // set the owning side to null (unless already changed)
            if ($reaction->getUser() === $this) {
                $reaction->setUser(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Comment[]
     */
    public function getComments(): Collection
    {
        return $this->comments;
    }

    public function addComment(Comment $comment): self
    {
        if (!$this->comments->contains($comment)) {
            $this->comments[] = $comment;
            $comment->setAuthor($this);
        }
        return $this;
    }

    /**
     * @return Collection|SubscribeResource[]
     */
    public function getSubscribeResources(): Collection
    {
        return $this->subscribeResources;
    }

    public function addSubscribeResource(SubscribeResource $subscribeResource): self
    {
        if (!$this->subscribeResources->contains($subscribeResource)) {
            $this->subscribeResources[] = $subscribeResource;
            $subscribeResource->setUser($this);
        }
        return $this;
    }


    public function removeComment(Comment $comment): self
    {
        if ($this->comments->removeElement($comment)) {
            // set the owning side to null (unless already changed)
            if ($comment->getAuthor() === $this) {
                $comment->setAuthor(null);
            }
        }
        return $this;
    }

    public function removeSubscribeResource(SubscribeResource $subscribeResource): self
    {
        if ($this->subscribeResources->removeElement($subscribeResource)) {
            // set the owning side to null (unless already changed)
            if ($subscribeResource->getUser() === $this) {
                $subscribeResource->setUser(null);
            }
        }
        return $this;
    }

    /**
     * @return Collection|SubscribeUser[]
     */
    public function getSubscribeUsers(): Collection
    {
        return $this->subscribeUsers;
    }

    public function addSubscribeUser(SubscribeUser $subscribeUser): self
    {
        if (!$this->subscribeUsers->contains($subscribeUser)) {
            $this->subscribeUsers[] = $subscribeUser;
            $subscribeUser->setUser($this);
        }

        return $this;
    }

    public function removeSubscribeUser(SubscribeUser $subscribeUser): self
    {
        if ($this->subscribeUsers->removeElement($subscribeUser)) {
            // set the owning side to null (unless already changed)
            if ($subscribeUser->getUser() === $this) {
                $subscribeUser->setUser(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|SubscribeUser[]
     */
    public function getSubscribedUsers(): Collection
    {
        return $this->subscribedUsers;
    }

    public function addSubscribedUser(SubscribeUser $subscribedUser): self
    {
        if (!$this->subscribedUsers->contains($subscribedUser)) {
            $this->subscribedUsers[] = $subscribedUser;
            $subscribedUser->setSubscribed($this);
        }

        return $this;
    }

    public function removeSubscribedUser(SubscribeUser $subscribedUser): self
    {
        if ($this->subscribedUsers->removeElement($subscribedUser)) {
            // set the owning side to null (unless already changed)
            if ($subscribedUser->getSubscribed() === $this) {
                $subscribedUser->setSubscribed(null);
            }
        }
        return $this;
    }
}
