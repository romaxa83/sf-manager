<?php
declare(strict_types=1);

namespace App\Model\Work\Entity\Projects\Project;

use App\Model\Work\Entity\Members\Member\Id as MemberId;
use App\Model\Work\Entity\Members\Member\Member;
use App\Model\Work\Entity\Projects\Project\Department\Department;
use App\Model\Work\Entity\Projects\Project\Department\Id as DepartmentId;
use App\Model\Work\Entity\Projects\Role\Role;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="work_projects_projects")
 */
class Project
{
    /**
     * @var Id
     * @ORM\Column(type="work_projects_project_id")
     * @ORM\Id
     */
    private $id;

    /**
     * @var string
     * @ORM\Column(type="string")
     */
    private $name;

    /**
     * @var int
     * @ORM\Column(type="integer")
     */
    private $sort;

    /**
     * @var Status
     * @ORM\Column(type="work_projects_project_status", length=16)
     */
    private $status;

    /**
     * @var ArrayCollection|Department[]
     * @ORM\OneToMany(
     *     targetEntity="App\Model\Work\Entity\Projects\Project\Department\Department",
     *     mappedBy="project", orphanRemoval=true, cascade={"all"}
     * )
     * @ORM\OrderBy({"name" = "ASC"})
     */
    private $departments;

    /**
     * @var ArrayCollection|Membership[]
     * @ORM\OneToMany(targetEntity="Membership", mappedBy="project", orphanRemoval=true, cascade={"all"})
     */
    private $memberships;

    /**
     * @ORM\Version()
     * @ORM\Column(type="integer")
     */
    private $version;

    public function __construct(Id $id, string $name, int $sort)
    {
        $this->id = $id;
        $this->name = $name;
        $this->sort = $sort;
        $this->status = Status::active();
        $this->departments = new ArrayCollection();
        $this->memberships = new ArrayCollection();
    }

    public function edit(string $name, int $sort): void
    {
        $this->name = $name;
        $this->sort = $sort;
    }

    // архивация проекта
    public function archive(): void
    {
        if ($this->isArchived()) {
            throw new \DomainException('Project is already archived.');
        }
        $this->status = Status::archived();
    }

    // разархивация проекта
    public function reinstate(): void
    {
        if ($this->isActive()) {
            throw new \DomainException('Project is already active.');
        }
        $this->status = Status::active();
    }

    //добавление отдела
    public function addDepartment(DepartmentId $id, string $name): void
    {
        foreach ($this->departments as $department) {
            //проверка на уникальность отдела
            if ($department->isNameEqual($name)) {
                throw new \DomainException('Department already exists.');
            }
        }
        $this->departments->add(new Department($this, $id, $name));
    }

    public function editDepartment(DepartmentId $id, string $name): void
    {
        foreach ($this->departments as $current) {
            if ($current->getId()->isEqual($id)) {
                $current->edit($name);
                return;
            }
        }
        throw new \DomainException('Department is not found.');
    }

    public function removeDepartment(DepartmentId $id): void
    {
        foreach ($this->departments as $department) {
            if ($department->getId()->isEqual($id)) {
                foreach ($this->memberships as $membership) {
                    if ($membership->isForDepartment($id)) {
                        throw new \DomainException('Unable to remove department with members.');
                    }
                }
                $this->departments->removeElement($department);
                return;
            }
        }
        throw new \DomainException('Department is not found.');
    }

    public function hasMember(MemberId $id): bool
    {
        foreach ($this->memberships as $membership) {
            if ($membership->isForMember($id)) {
                return true;
            }
        }
        return false;
    }

    /**
     * @param Member $member
     * @param DepartmentId[] $departmentIds
     * @param Role[] $roles
     * @throws \Exception
     */
    public function addMember(Member $member, array $departmentIds, array $roles): void
    {
        foreach ($this->memberships as $membership) {
            if ($membership->isForMember($member->getId())) {
                throw new \DomainException('Member already exists.');
            }
        }
        $departments = array_map([$this, 'getDepartment'], $departmentIds);
        $this->memberships->add(new Membership($this, $member, $departments, $roles));
    }

    /**
     * @param MemberId $member
     * @param DepartmentId[] $departmentIds
     * @param Role[] $roles
     */
    public function editMember(MemberId $member, array $departmentIds, array $roles): void
    {
        foreach ($this->memberships as $membership) {
            if ($membership->isForMember($member)) {
                $membership->changeDepartments(array_map([$this, 'getDepartment'], $departmentIds));
                $membership->changeRoles($roles);
                return;
            }
        }
        throw new \DomainException('Member is not found.');
    }

    public function removeMember(MemberId $member): void
    {
        foreach ($this->memberships as $membership) {
            if ($membership->isForMember($member)) {
                $this->memberships->removeElement($membership);
                return;
            }
        }
        throw new \DomainException('Member is not found.');
    }

    //проверяет если у пользователя данное разрешение
    public function isMemberGranted(MemberId $id, string $permission): bool
    {
        foreach ($this->memberships as $membership) {
            if ($membership->isForMember($id)) {
                return $membership->isGranted($permission);
            }
        }
        return false;
    }

    public function isArchived(): bool
    {
        return $this->status->isArchived();
    }

    public function isActive(): bool
    {
        return $this->status->isActive();
    }

    public function getId(): Id
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getSort(): int
    {
        return $this->sort;
    }

    public function getStatus(): Status
    {
        return $this->status;
    }

    public function getDepartments()
    {
        return $this->departments->toArray();
    }

    public function getDepartment(DepartmentId $id): Department
    {
        foreach ($this->departments as $department) {
            if ($department->getId()->isEqual($id)) {
                return $department;
            }
        }
        throw new \DomainException('Department is not found.');
    }

    public function getMemberships()
    {
        return $this->memberships->toArray();
    }

    public function getMembership(MemberId $id): Membership
    {
        foreach ($this->memberships as $membership) {
            if ($membership->isForMember($id)) {
                return $membership;
            }
        }
        throw new \DomainException('Member is not found.');
    }
}