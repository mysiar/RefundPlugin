<?php

declare(strict_types=1);

namespace Tests\Sylius\RefundPlugin\Behat\Context\Ui;

use Behat\Behat\Context\Context;
use Sylius\Behat\NotificationType;
use Sylius\Behat\Service\NotificationCheckerInterface;
use Sylius\Component\Core\Model\OrderInterface;
use Tests\Sylius\RefundPlugin\Behat\Page\Order\ShowPageInterface;
use Webmozart\Assert\Assert;

final class ManagingOrdersContext implements Context
{
    /** @var ShowPageInterface */
    private $showPage;

    /** @var NotificationCheckerInterface */
    private $notificationChecker;

    public function __construct(
        ShowPageInterface $showPage,
        NotificationCheckerInterface $notificationChecker
    ) {
        $this->showPage = $showPage;
        $this->notificationChecker = $notificationChecker;
    }

    /**
     * @Given I am viewing the summary of the order :order
     */
    public function viewingTheSummaryOfTheOrder(OrderInterface $order): void
    {
        $this->showPage->open(['id' => $order->getId()]);
    }

    /**
     * @Then I should be notified that the order should be paid
     */
    public function shouldBeNotifiedThatTheOrderShouldBePaid(): void
    {
        $this->notificationChecker->checkNotification(
            'Order should be paid for the units to could be refunded',
            NotificationType::failure()
        );
    }

    /**
     * @Then I should not be able to see refunds button
     */
    public function shouldNotBeAbleToSeeRefundsButton(): void
    {
        Assert::false($this->showPage->hasRefundsButton());
    }
}