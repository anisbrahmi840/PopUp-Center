<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Vich\UploaderBundle\Mapping\Annotation as Vich;

/**
 * @ORM\Entity(repositoryClass="App\Repository\CourseRepository")
 * @Vich\Uploadable
 */
class Course
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    /**
     * @ORM\Column(type="float")
     */
    private $price;

    /**
     * @ORM\Column(type="integer")
     */
    private $total_students;

    /**
     * @ORM\Column(type="integer")
     */
    private $course_duration;

    /**
     * @ORM\Column(type="date")
     */
    private $course_start;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $palace;

    /**
     * @ORM\Column(type="integer")
     */
    private $days;

    /**
     * @ORM\Column(type="string", length=255, nullable=true )
     */
    private $image;

    /**
     * @Vich\UploadableField(mapping="courses_images", fileNameProperty="image")
     * @var File
     */
    private $imageFile;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $update_at;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $description;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\User", inversedBy="courses")
     */
    private $users;

    /**
     * @ORM\OneToOne(targetEntity="App\Entity\User", inversedBy="course", cascade={"persist", "remove"})
     * @ORM\JoinColumn(nullable=false)
     */
    private $professor;

    public function __construct()
    {
        $this->users = new ArrayCollection();
    }



    public function getId(): ?int
    {
        return $this->id;
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

    public function getPrice(): ?float
    {
        return $this->price;
    }

    public function setPrice(float $price): self
    {
        $this->price = $price;

        return $this;
    }

    public function getTotalStudents(): ?int
    {
        return $this->total_students;
    }

    public function setTotalStudents(int $total_students): self
    {
        $this->total_students = $total_students;

        return $this;
    }

    public function getCourseDuration(): ?int
    {
        return $this->course_duration;
    }

    public function setCourseDuration(int $course_duration): self
    {
        $this->course_duration = $course_duration;

        return $this;
    }

    public function getCourseStart(): ?\DateTimeInterface
    {
        return $this->course_start;
    }

    public function setCourseStart(\DateTimeInterface $course_start): self
    {
        $this->course_start = $course_start;

        return $this;
    }

    public function getPalace(): ?string
    {
        return $this->palace;
    }

    public function setPalace(string $palace): self
    {
        $this->palace = $palace;

        return $this;
    }

    public function getDays(): ?int
    {
        return $this->days;
    }

    public function setDays(int $days): self
    {
        $this->days = $days;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getImage()
    {
        return $this->image;
    }

    /**
     * @param mixed $image
     */
    public function setImage($image): void
    {
        $this->image = $image;
    }


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
            $this->updatedAt = new \DateTime('now');
        }
        if (null !== $imageFile) {
            // It is required that at least one field changes if you are using doctrine
            // otherwise the event listeners won't be called and the file is lost
            $this->updatedAt = new \DateTimeImmutable();
        }
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

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getUsers(): ?User
    {
        return $this->users;
    }

    public function setUsers(?User $users): self
    {
        $this->users = $users;

        return $this;
    }

    public function addUser(User $user): self
    {
        if (!$this->users->contains($user)) {
            $this->users[] = $user;
        }

        return $this;
    }

    public function removeUser(User $user): self
    {
        if ($this->users->contains($user)) {
            $this->users->removeElement($user);
        }

        return $this;
    }

    public function getProfessor(): ?User
    {
        return $this->professor;
    }

    public function setProfessor(User $professor): self
    {
        $this->professor = $professor;

        return $this;
    }




}
