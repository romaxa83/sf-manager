<?php

declare(strict_types=1);

namespace App\Security\Voter\Work\Projects;

use App\Model\Work\Entity\Members\Member\Id;
use App\Model\Work\Entity\Projects\Project\Project;
use App\Model\Work\Entity\Projects\Role\Permission;
use App\Security\UserIdentity;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;

class ProjectAccess extends Voter
{
    public const VIEW = 'view';
    public const MANAGE_MEMBERS = 'manage_members';
    public const EDIT = 'edit';
    private $security;

    public function __construct(AuthorizationCheckerInterface $security)
    {
        $this->security = $security;
    }

    /**
     * проверяет приминим ли voter для того что мы вызываем,т.е.
     * при вызове $this->denyAccessUnlessGranted(ProjectAccess::MANAGE_MEMBERS, $project);
     * $project попадет в аргумент $subject ,соответствено нужно вернуть true
     * если $project реально являеться проектом
     * Если мы вернем true ,symfony запросит метод voteOnAttribute
     */
    protected function supports($attribute, $subject): bool
    {
        return in_array($attribute, [self::VIEW, self::MANAGE_MEMBERS, self::EDIT], true) && $subject instanceof Project;
    }

    /**
     * из $token можно получить текущего пользователя
     * и проверить у него доступ ,ели вернем true то пользователь получит доступ
     */
    protected function voteOnAttribute($attribute, $subject, TokenInterface $token): bool
    {
        $user = $token->getUser();
        if (!$user instanceof UserIdentity) {
            return false;
        }

        if (!$subject instanceof Project) {
            return false;
        }

        switch ($attribute) {
            case self::VIEW:
                return
                    $this->security->isGranted('ROLE_WORK_MANAGE_PROJECTS') ||
                    $subject->hasMember(new Id($user->getId()));
                break;
            case self::EDIT:
                return $this->security->isGranted('ROLE_WORK_MANAGE_PROJECTS');
                break;
            case self::MANAGE_MEMBERS:
                return
                    $this->security->isGranted('ROLE_WORK_MANAGE_PROJECTS') ||
                    $subject->isMemberGranted(new Id($user->getId()), Permission::MANAGE_PROJECT_MEMBERS);
                break;
        }

        return false;
    }
}