<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use FOS\UserBundle\Model\User as BaseUser;
use phpDocumentor\Reflection\Types\Integer;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\File\File;
use Vich\UploaderBundle\Mapping\Annotation as Vich;

/**
 * @ORM\Entity(repositoryClass="App\Repository\UserRepository")
 * @Vich\Uploadable
 * @ORM\Table(name="`user`")
 */
class User extends BaseUser
{
    const ROLES = [
        'ROLE_ADMIN' => 'ROLE_ADMIN',
        'ROLE_USER' => 'ROLE_USER'
    ];

    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    protected $id;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $age;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Course", mappedBy="users")
     */
    private $courses;

    /**
     * @ORM\OneToOne(targetEntity="App\Entity\Course", mappedBy="professor", cascade={"persist", "remove"})
     */
    private $course;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $image;

    /**
     * @Vich\UploadableField(mapping="user_image", fileNameProperty="image")
     * @var File
     */
    private $imageFile;

    public function getImageFile()
    {
        return $this->imageFile;
    }

    /**
     * @param File $imageFile
     */
    public function setImageFile(File $imageFile): void
    {
        $this->imageFile = $imageFile;
        if ($this->imageFile instanceof UploadedFile) {
            $this->update_at = new \DateTime('now');
        }
        if (null !== $imageFile) {
            // It is required that at least one field changes if you are using doctrine
            // otherwise the event listeners won't be called and the file is lost
            $this->update_at = new \DateTimeImmutable();
        };
    }


    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $update_at;


    public function __construct()
    {
        parent::__construct();
        $this->courses = new ArrayCollection();
    }

    public function getId()
    {
        return $this->id;
    }

    public function getAge()
    {
        return $this->age;
    }

    public function setAge(Integer $age): self
    {
        $this->age = $age;

        return $this;
    }

    /**
     * @return Collection|Course[]
     */
    public function getCourses(): Collection
    {
        return $this->courses;
    }

    public function addCourse(Course $course): self
    {
        if (!$this->courses->contains($course)) {
            $this->courses[] = $course;
            $course->setUsers($this);
        }

        return $this;
    }

    public function removeCourse(Course $course): self
    {
        if ($this->courses->contains($course)) {
            $this->courses->removeElement($course);
            // set the owning side to null (unless already changed)
            if ($course->getUsers() === $this) {
                $course->setUsers(null);
            }
        }

        return $this;
    }

    public function getCourse(): ?Course
    {
        return $this->course;
    }

    public function setCourse(Course $course): self
    {
        $this->course = $course;

        // set the owning side of the relation if necessary
        if ($this !== $course->getProfessor()) {
            $course->setProfessor($this);
        }

        return $this;
    }

    public function getImage(): ?string
    {
        return $this->image;
    }

    public function setImage(?string $image): self
    {
        $this->image = $image;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getUpdateAt()
    {
        return $this->update_at;
    }

    /**
     * @param mixed $update_at
     */
    public function setUpdateAt($update_at): void
    {
        $this->update_at = $update_at;
    }
}
