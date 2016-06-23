<?php
namespace PITS\PitsSiteStatistics\Domain\Repository;

/***************************************************************
 *
 *  Copyright notice
 *
 *  (c) 2016 Ricky Mathew <ricky.mk@pitsolutions.com>, Pit Solutions
 *
 *  All rights reserved
 *
 *  This script is part of the TYPO3 project. The TYPO3 project is
 *  free software; you can redistribute it and/or modify
 *  it under the terms of the GNU General Public License as published by
 *  the Free Software Foundation; either version 3 of the License, or
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
 * The repository for Statistics
 */
class StatisticsRepository extends \TYPO3\CMS\Extbase\Persistence\Repository
{
    public function getDefaultAnalyticAccount()
    {
        $defaultAccounts = $this->findByDefaultAccount(1);
        $obj_array       = $defaultAccounts->toArray();

        if (sizeof($obj_array) > 0) {
            return $defaultAccounts[0];
        } else {
            return false;
        }

    }
}
