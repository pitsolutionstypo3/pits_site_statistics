<?php

namespace PITS\PitsSiteStatistics\Tests\Unit\Domain\Model;

/***************************************************************
 *  Copyright notice
 *
 *  (c) 2016 Ricky Mathew <ricky.mk@pitsolutions.com>, Pit Solutions 
 *
 *  All rights reserved
 *
 *  This script is part of the TYPO3 project. The TYPO3 project is
 *  free software; you can redistribute it and/or modify
 *  it under the terms of the GNU General Public License as published by
 *  the Free Software Foundation; either version 2 of the License, or
 *  (at your option) any later version.
 *
 *  The GNU General Public License can be found at
 *  http://www.gnu.org/copyleft/gpl.html.
 *
 *  This script is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *  GNU General Public License for more details.
 *
 *  This copyright notice MUST APPEAR in all copies of the script!
 ***************************************************************/

/**
 * Test case for class \PITS\PitsSiteStatistics\Domain\Model\Statistics.
 *
 * @copyright Copyright belongs to the respective authors
 * @license http://www.gnu.org/licenses/gpl.html GNU General Public License, version 3 or later
 *
 * @author Ricky Mathew <ricky.mk@pitsolutions.com>
 */
class StatisticsTest extends \TYPO3\CMS\Core\Tests\UnitTestCase
{
	/**
	 * @var \PITS\PitsSiteStatistics\Domain\Model\Statistics
	 */
	protected $subject = NULL;

	public function setUp()
	{
		$this->subject = new \PITS\PitsSiteStatistics\Domain\Model\Statistics();
	}

	public function tearDown()
	{
		unset($this->subject);
	}

	/**
	 * @test
	 */
	public function getServiceAccountEmailReturnsInitialValueForString()
	{
		$this->assertSame(
			'',
			$this->subject->getServiceAccountEmail()
		);
	}

	/**
	 * @test
	 */
	public function setServiceAccountEmailForStringSetsServiceAccountEmail()
	{
		$this->subject->setServiceAccountEmail('Conceived at T3CON10');

		$this->assertAttributeEquals(
			'Conceived at T3CON10',
			'serviceAccountEmail',
			$this->subject
		);
	}

	/**
	 * @test
	 */
	public function getKeyFileLocationReturnsInitialValueForString()
	{
		$this->assertSame(
			'',
			$this->subject->getKeyFileLocation()
		);
	}

	/**
	 * @test
	 */
	public function setKeyFileLocationForStringSetsKeyFileLocation()
	{
		$this->subject->setKeyFileLocation('Conceived at T3CON10');

		$this->assertAttributeEquals(
			'Conceived at T3CON10',
			'keyFileLocation',
			$this->subject
		);
	}

	/**
	 * @test
	 */
	public function getPageViewsReturnsInitialValueForInt()
	{	}

	/**
	 * @test
	 */
	public function setPageViewsForIntSetsPageViews()
	{	}

	/**
	 * @test
	 */
	public function getUserViewsReturnsInitialValueForInt()
	{	}

	/**
	 * @test
	 */
	public function setUserViewsForIntSetsUserViews()
	{	}

	/**
	 * @test
	 */
	public function getVar1ViewsReturnsInitialValueForInt()
	{	}

	/**
	 * @test
	 */
	public function setVar1ViewsForIntSetsVar1Views()
	{	}

	/**
	 * @test
	 */
	public function getVar3ViewsReturnsInitialValueForInt()
	{	}

	/**
	 * @test
	 */
	public function setVar3ViewsForIntSetsVar3Views()
	{	}

	/**
	 * @test
	 */
	public function getVar2ViewsReturnsInitialValueForInt()
	{	}

	/**
	 * @test
	 */
	public function setVar2ViewsForIntSetsVar2Views()
	{	}
}
