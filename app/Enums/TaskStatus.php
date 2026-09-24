<?php

namespace App\Enums;

enum TaskStatus: string
{
    case Pending = 'pending';
    case InProgress = 'in_progress';
    case OnHold = 'on_hold';
    case Delayed = 'delayed';
    case PendingReview = 'pending_review';
    case Completed = 'completed';
    case Cancelled = 'cancelled';

    public function label(): string
    {
        return match ($this) {
            self::Pending => 'To Do',
            self::InProgress => 'In Progress',
            self::OnHold => 'On Hold',
            self::Delayed => 'Delayed',
            self::PendingReview => 'Completed',
            self::Completed => 'Approved',
            self::Cancelled => 'Cancelled',
        };
    }

    public function badgeClasses(): string
    {
        return match ($this) {
            self::Pending => 'bg-subtle text-muted',
            self::InProgress => 'bg-info-soft text-info',
            self::OnHold => 'bg-warning-soft text-warning',
            self::Delayed => 'bg-danger-soft text-danger',
            self::PendingReview => 'bg-primary-soft text-primary',
            self::Completed => 'bg-success-soft text-success',
            self::Cancelled => 'bg-subtle text-faint',
        };
    }

    /**
     * Allowed manual transitions FROM this status, keyed by who may perform them.
     * 'employee' = assignee, 'reviewer' = manager/team lead with authority over the task.
     */
    public function allowedTransitions(): array
    {
        return match ($this) {
            self::Pending => [self::InProgress->value => ['employee', 'reviewer']],
            self::InProgress => [
                self::OnHold->value => ['employee', 'reviewer'],
                self::PendingReview->value => ['employee', 'reviewer'],
                self::Delayed->value => ['employee', 'reviewer'], // via delay request, see TaskStatusService
                self::Cancelled->value => ['reviewer'],
            ],
            self::OnHold => [
                self::InProgress->value => ['employee', 'reviewer'],
                self::Cancelled->value => ['reviewer'],
            ],
            self::Delayed => [
                self::InProgress->value => ['reviewer'], // via delay approval/rejection, see TaskStatusService
                self::Cancelled->value => ['reviewer'],
            ],
            self::PendingReview => [
                self::Completed->value => ['reviewer'],
                self::InProgress->value => ['reviewer'], // sent back
            ],
            self::Completed => [],
            self::Cancelled => [],
        };
    }

    public function canManuallyTransitionTo(TaskStatus $target, string $actorRole): bool
    {
        $allowed = $this->allowedTransitions();

        return isset($allowed[$target->value]) && in_array($actorRole, $allowed[$target->value], true);
    }
}
