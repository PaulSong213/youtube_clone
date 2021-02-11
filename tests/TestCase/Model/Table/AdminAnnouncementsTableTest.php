<?php
declare(strict_types=1);

namespace App\Test\TestCase\Model\Table;

use App\Model\Table\AdminAnnouncementsTable;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\AdminAnnouncementsTable Test Case
 */
class AdminAnnouncementsTableTest extends TestCase
{
    /**
     * Test subject
     *
     * @var \App\Model\Table\AdminAnnouncementsTable
     */
    protected $AdminAnnouncements;

    /**
     * Fixtures
     *
     * @var array
     */
    protected $fixtures = [
        'app.AdminAnnouncements',
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp(): void
    {
        parent::setUp();
        $config = $this->getTableLocator()->exists('AdminAnnouncements') ? [] : ['className' => AdminAnnouncementsTable::class];
        $this->AdminAnnouncements = $this->getTableLocator()->get('AdminAnnouncements', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown(): void
    {
        unset($this->AdminAnnouncements);

        parent::tearDown();
    }

    /**
     * Test validationDefault method
     *
     * @return void
     */
    public function testValidationDefault(): void
    {
        $this->markTestIncomplete('Not implemented yet.');
    }
}
