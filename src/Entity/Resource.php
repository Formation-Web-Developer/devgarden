<?php

namespace App\Entity;

use App\Repository\ResourceRepository;
use App\Utils\State;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ResourceRepository::class)
 */
class Resource
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="resources")
     * @ORM\JoinColumn(nullable=false)
     */
    private $user;

    /**
     * @ORM\ManyToOne(targetEntity=Category::class, inversedBy="resources")
     * @ORM\JoinColumn(nullable=false)
     */
    private $category;

    /**
     * @ORM\Column(type="string", length=50)
     */
    private $name;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $description;

    /**
     * @ORM\Column(type="text")
     */
    private $content;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $slug;

    /**
     * @ORM\Column(type="datetime")
     */
    private $created_at;

    /**
     * @ORM\OneToMany(targetEntity=PatchNote::class, mappedBy="resource", orphanRemoval=true)
     */
    private $patchNotes;

    /**
     * @ORM\OneToMany(targetEntity=Reaction::class, mappedBy="resource", orphanRemoval=true)
     */
    private $reactions;

    /**
     * @ORM\OneToMany(targetEntity=Comment::class, mappedBy="resource", orphanRemoval=true)
     */
    private $comments;

    /**
     * @ORM\OneToMany(targetEntity=SubscribeResource::class, mappedBy="resource", orphanRemoval=true)
     */
    private $subscribeResources;

    /**
     * @var PatchNote|bool|null
     */
    private $latest = null;

    /**
     * @ORM\Column(type="integer")
     */
    private $validation;

    public function __construct()
    {
        $this->created_at = new \DateTime();
        $this->patchNotes = new ArrayCollection();
        $this->reactions = new ArrayCollection();
        $this->comments = new ArrayCollection();
        $this->subscribeResources = new ArrayCollection();
        $this->validation = State::WAITING;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }

    public function getCategory(): ?Category
    {
        return $this->category;
    }

    public function setCategory(?Category $category): self
    {
        $this->category = $category;

        return $this;
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

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getContent(): ?string
    {
        return $this->content;
    }

    public function setContent(string $content): self
    {
        $this->content = $content;

        return $this;
    }

    public function getSlug(): ?string
    {
        return $this->slug;
    }

    public function setSlug(string $slug): self
    {
        $this->slug = $slug;

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

    /**
     * @return Collection|PatchNote[]
     */
    public function getPatchNotes(): Collection
    {
        return $this->patchNotes;
    }

    public function addPatchNote(PatchNote $patchNote): self
    {
        if (!$this->patchNotes->contains($patchNote)) {
            $this->patchNotes[] = $patchNote;
            $patchNote->setResource($this);
        }

        return $this;
    }

    public function removePatchNote(PatchNote $patchNote): self
    {
        if ($this->patchNotes->removeElement($patchNote)) {
            // set the owning side to null (unless already changed)
            if ($patchNote->getResource() === $this) {
                $patchNote->setResource(null);
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
            $reaction->setResource($this);
        }

        return $this;
    }

    public function removeReaction(Reaction $reaction): self
    {
        if ($this->reactions->removeElement($reaction)) {
            // set the owning side to null (unless already changed)
            if ($reaction->getResource() === $this) {
                $reaction->setResource(null);
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
            $comment->setResource($this);
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
            $subscribeResource->setResource($this);
        }
        return $this;
    }

    public function removeComment(Comment $comment): self
    {
        if ($this->comments->removeElement($comment)) {
            // set the owning side to null (unless already changed)
            if ($comment->getResource() === $this) {
                $comment->setResource(null);
            }
        }
        return $this;
    }


    public function removeSubscribeResource(SubscribeResource $subscribeResource): self
    {
        if ($this->subscribeResources->removeElement($subscribeResource)) {
            // set the owning side to null (unless already changed)
            if ($subscribeResource->getResource() === $this) {
                $subscribeResource->setResource(null);
            }
        }
        return $this;
    }

    /**
     * @return PatchNote|bool|null
     */
    public function getLatest()
    {
        if (is_null($this->latest)) {
            $this->latest = $this->getPatchNotes()
                ->filter(fn ($p) => $p->getLatest())
                ->first() ?: false;
        }
        return $this->latest;
    }

    public function getValidation(): ?int
    {
        return $this->validation;
    }

    public function setValidation(int $validation): self
    {
        $this->validation = $validation;

        return $this;
    }
}
