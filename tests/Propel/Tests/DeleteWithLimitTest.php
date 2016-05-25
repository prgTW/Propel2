<?php
/**
 * This file is part of the Propel package.
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * @license MIT License
 */
namespace Propel\Tests;

use Propel\Generator\Util\QuickBuilder;
use Propel\Tests\TestCase;

/**
 * Test if *Query::delete() supports limiting
 *
 * @group mysql
 */
class DeleteWithLimitTest extends TestCase
{
    protected function setUp()
    {
        parent::setUp();
        if (!class_exists('\DeleteWithLimit')) {
            $schema = '
            <database name="DeleteWithLimit" defaultIdMethod="native">
                <table name="DeleteWithLimit">
                    <column name="id" primaryKey="true" type="INTEGER" autoincrement="true"/>
                    <column name="whatever" type="INTEGER" />
                </table>
            </database>
            ';
            QuickBuilder::buildSchema($schema);
        }
    }

    public function testDeletingWithALimit()
    {
        $count = (new \DeleteWithLimitQuery)->count();
        $this->assertSame(0, $count, 'There shouldn\'t be any entities in the table');

        (new \DeleteWithLimit)->setWhatever(1)->save();
        (new \DeleteWithLimit)->setWhatever(1)->save();
        (new \DeleteWithLimit)->setWhatever(1)->save();

        $count = (new \DeleteWithLimitQuery)->count();
        $this->assertSame(3, $count, 'There should be 3 entities in the table');

        (new \DeleteWithLimitQuery)
            ->filterByWhatever(1)
            ->limit(1)
            ->delete();

        $count = (new \DeleteWithLimitQuery)->count();
        $this->assertSame(2, $count, 'There should be 2 entities in the table');
    }
}
