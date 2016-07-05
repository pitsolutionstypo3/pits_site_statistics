<?php
namespace PITS\PitsSiteStatistics\Controller;

use PITS\PitsSiteStatistics\Library\AnalyticsLibrary;

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
 * StatisticsController
 */
class StatisticsController extends \TYPO3\CMS\Extbase\Mvc\Controller\ActionController
{

    /**
     * AnalyticsLibrary
     * @var PITS\PitsSiteStatistics\Library\AnalyticsLibrary
     * @inject
     */
    protected $googleAnalytics;

    /**
     * StatisticsModel
     * @var PITS\PitsSiteStatistics\Domain\Model\Statistics
     * @inject
     */
    protected $statisticsModel;

    /**
     * StatisticsRepository
     * @var PITS\PitsSiteStatistics\Domain\Repository\StatisticsRepository
     * @inject
     */
    protected $statisticsRepository;

    /**
     * defaultMetrics
     */
    protected $defaultMetrics;

    /**
     * defaultDimension
     */
    protected $defaultDimension;

    /**
     * defaultStartDate
     */
    protected $defaultStartDate;

    /**
     * defaultEndDate
     */
    protected $defaultEndDate;

    /**
     * client
     */
    public $client;

    /**
     * analytics
     */
    public $analytics;

    public function __construct()
    {
        //sets defalt metric and dimension parameter
        $this->defaultMetrics   = 'ga:sessions';
        $this->defaultDimension = 'ga:country';
        $now                    = new \DateTime();
        //sets default start date and end date
        $end_date               = $now->format('Y-m-d');
        $start_date             = $now->modify('-1 month')->format('Y-m-d');
        $this->defaultStartDate = $start_date;
        $this->defaultEndDate   = $end_date;

    }

    /**
     * Default action
     * @return type
     */

    public function indexAction()
    {
        //getting messages to display
        if ($this->request->hasArgument('alertmessage')) {
            $alertmessage = $this->request->getArgument('alertmessage');
            $this->view->assign('alertmessage', $alertmessage);
        }
        $profile_found      = 0;
        $noaccounts         = 1;
        $dates              = [];
        $dates['startdate'] = $this->defaultStartDate;
        $dates['enddate']   = $this->defaultEndDate;
        //geting default analytic account details
        $allAnalyticsAccounts = $this->statisticsRepository->findAll();
        $obj_array            = $allAnalyticsAccounts->toArray();
        if (sizeof($obj_array) > 0) {

            try {
                $noaccounts              = 0;
                $selectedAccount         = [];
                $defaultAnalyticsAccount = $this->statisticsRepository->getDefaultAnalyticAccount();
                if (!$defaultAnalyticsAccount) {
                    $defaultAnalyticsAccount = $allAnalyticsAccounts[0];
                }
                $serviceAccountEmail = $defaultAnalyticsAccount->getServiceAccountEmail();
                $keyFileLocation     = $defaultAnalyticsAccount->getKeyFileLocation();
                $webTitle            = $defaultAnalyticsAccount->getWebTitle();

                $trackingId = $defaultAnalyticsAccount->getPropertyTrackingid();

                //assigning selected account details
                $selectedAccount['webTitle']            = $webTitle;
                $selectedAccount['serviceAccountEmail'] = $serviceAccountEmail;
                $selectedAccount['keyFileLocation']     = $keyFileLocation;

                //set preselector values
                $preselector              = [];
                $preselector['Metric']    = $this->defaultMetrics;
                $preselector['Dimension'] = $this->defaultDimension;
                $preselector['Account']   = $defaultAnalyticsAccount->getUid();

                //getting analytics servics and profile
                $analyticHandler = $this->googleAnalytics->getAnalyticsAccount($serviceAccountEmail, $keyFileLocation);
                $analytics       = $analyticHandler['analytics'];
                $client          = $analyticHandler['client'];

                //set client and analytics
                $this->setClient($client);
                $this->setAnalytics($analytics);

                $profile       = $this->googleAnalytics->getFirstProfileId($analytics, $trackingId);
                $profile_found = 1;
                $results       = $this->googleAnalytics->getResults($analytics, $profile, $this->defaultStartDate, $this->defaultEndDate, $this->defaultMetrics, $this->defaultDimension);
                $rows          = $results->getRows();

                //Format and output data as JSON
                $Barchart   = json_encode($this->googleAnalytics->drawBarChart($rows, $this->defaultMetrics, $this->defaultDimension));
                $DonutChart = json_encode($this->googleAnalytics->drawDonutChart($rows, $this->defaultMetrics, $this->defaultDimension));
                $LineChart  = json_encode($this->googleAnalytics->drawLineChart($rows, $this->defaultMetrics, $this->defaultDimension));
                //getting metrics and dimensions
                $allMetrics    = $this->getMetrics();
                $allDimensions = $this->getDimensions();

                //getting accounts
                $allAccounts = $this->getAccounts();

                $gaParameters              = [];
                $gaParameters['metric']    = $this->googleAnalytics->getMetricParamter($this->defaultMetrics);
                $gaParameters['dimension'] = $this->googleAnalytics->getDimensionParamter($this->defaultDimension);
                //assigning variables to view
                $this->view->assign('BarData', $Barchart);
                $this->view->assign('DonutData', $DonutChart);
                $this->view->assign('LineData', $LineChart);
                $this->view->assign('preselector', $preselector);
                $this->view->assign('metrics', $allMetrics);
                $this->view->assign('dimensions', $allDimensions);
                $this->view->assign('Dates', $dates);
                $this->view->assign('accounts', $allAccounts);
                $this->view->assign('profile_found', $profile_found);
                $this->view->assign('gaParamters', $gaParameters);
                $this->view->assign('seletedAccount', $selectedAccount);
                $this->view->assign('noaccounts', $noaccounts);
            } catch (\Exception $e) {
                $alertmessage            = [];
                $alertmessage['message'] = '<i class="fa fa-exclamation-triangle" aria-hidden="true"></i> ' . $e->getMessage();
                $alertmessage['type']    = 'warning';
                $this->view->assign('alertmessage', $alertmessage);
                $this->view->assign('profile_found', $profile_found);
            }
        } else {
            $alertmessage            = [];
            $alertmessage['message'] = '<i class="fa fa-exclamation-triangle" aria-hidden="true"></i> No analytics account is configured.Please add atleast one';
            $alertmessage['type']    = 'warning';
            $this->view->assign('profile_found', $profile_found);
            $this->view->assign('alertmessage', $alertmessage);
            $this->view->assign('noaccounts', $noaccounts);
        }
    }

    /**
     * Filtering data based on  parameters given
     * @return type
     */
    public function filterAction()
    {
        //getting analytics servics and profile
        $dataresults   = 0; //0 for no data found //1 for results found //2 for can't match this parameters
        $profile_found = 0;
        //getting arguments
        $selectedAccount = [];
        $arguments       = $this->request->getArguments();
        $account_uid     = $arguments['account'];
        $gaMetrics       = $arguments['metrics'];
        $gaDimension     = $arguments['dimensions'];
        $startdate       = $_POST['startdate'];
        $enddate         = $_POST['enddate'];

        //sets end date and start date
        $dates = [];
        if ($startdate != '') {
            $dates['startdate'] = $startdate;
        } else {
            $dates['startdate'] = $this->defaultStartDate;
        }

        if ($enddate != '') {
            $dates['enddate'] = $enddate;
        } else {
            $dates['enddate'] = $this->defaultEndDate;
        }

        //set preselector values
        $preselector              = [];
        $preselector['Metric']    = $gaMetrics;
        $preselector['Dimension'] = $gaDimension;
        $preselector['Account']   = $account_uid;

        //getting analytics account
        $analyticsAccount    = $this->statisticsRepository->findByUid($account_uid);
        $serviceAccountEmail = $analyticsAccount->getServiceAccountEmail();
        $keyFileLocation     = $analyticsAccount->getKeyFileLocation();
        $webTitle            = $analyticsAccount->getWebTitle();
        $trackingId          = $analyticsAccount->getPropertyTrackingid();

        //assigning selected account details
        $selectedAccount['webTitle']            = $webTitle;
        $selectedAccount['serviceAccountEmail'] = $serviceAccountEmail;
        $selectedAccount['keyFileLocation']     = $keyFileLocation;

        try {
            //getting analytics services
            $analyticHandler = $this->googleAnalytics->getAnalyticsAccount($serviceAccountEmail, $keyFileLocation);
            $analytics       = $analyticHandler['analytics'];
            $client          = $analyticHandler['client'];

            //set client and analytics
            $this->setClient($client);
            $this->setAnalytics($analytics);

            //getting present ga parameters name
            $gaParameters['metric']    = $this->googleAnalytics->getMetricParamter($gaMetrics);
            $gaParameters['dimension'] = $this->googleAnalytics->getDimensionParamter($gaDimension);

            //getting all metrics and all dimensions
            $allMetrics    = $this->getMetrics($analyticsAccount);
            $allDimensions = $this->getDimensions($analyticsAccount);

            //getting accounts
            $allAccounts = $this->getAccounts();

            $profile       = $this->googleAnalytics->getFirstProfileId($analytics, $trackingId);
            $profile_found = 1;
            $results       = $this->googleAnalytics->getResults($analytics, $profile, $dates['startdate'], $dates['enddate'], $gaMetrics, $gaDimension);

            if (!is_object($results)) {
                $dataresults = 2;
            } else {
                $rows  = $results->getRows();
                $index = 0;
                if (sizeof($rows)) {
                    foreach ($rows as $row) {
                        if ($row[0] == '(not set)') {
                            //remove the 'not set' data.
                            unset($rows[$index]);
                        }
                        $index++;
                    }
                }
                if (sizeof($rows) > 0) {
                    $dataresults = 1;
                    //Format and output data as JSON
                    $Barchart   = json_encode($this->googleAnalytics->drawBarChart($rows, $gaMetrics, $gaDimension));
                    $DonutChart = json_encode($this->googleAnalytics->drawDonutChart($rows, $gaMetrics, $gaDimension));
                    $LineChart  = json_encode($this->googleAnalytics->drawLineChart($rows, $gaMetrics, $gaDimension));

                    //assigning variables to view
                    $this->view->assign('BarData', $Barchart);
                    $this->view->assign('DonutData', $DonutChart);
                    $this->view->assign('LineData', $LineChart);

                }

            }

        } catch (\Exception $e) {
            $alertmessage            = [];
            $alertmessage['message'] = '<i class="fa fa-exclamation-triangle" aria-hidden="true"></i> ' . $e->getMessage();
            $alertmessage['type']    = 'warning';
            $this->view->assign('alertmessage', $alertmessage);
            $this->view->assign('profile_found', $profile_found);
        }
        $this->view->assign('profile_found', $profile_found);
        $this->view->assign('dataResults', $dataresults);
        $this->view->assign('metrics', $allMetrics);
        $this->view->assign('dimensions', $allDimensions);
        $this->view->assign('accounts', $allAccounts);
        $this->view->assign('Dates', $dates);
        $this->view->assign('gaParamters', $gaParameters);
        $this->view->assign('preselector', $preselector);
        $this->view->assign('seletedAccount', $selectedAccount);

    }

    /**
     * Add Form action
     * @param \PITS\PitsSiteStatistics\Domain\Model\Statistics
     */
    public function addFormAction(\PITS\PitsSiteStatistics\Domain\Model\Statistics
         $statistics = null) {
        $this->view->assign('statistics', $statistics);
    }

    /**
     * Edit Form action
     * @param string
     */
    public function editFormAction()
    {
        $uid        = $this->request->getArgument('statistics');
        $statistics = $this->statisticsRepository->findByUid($uid);
        $this->view->assign('statistics', $statistics);
    }

    /**
     * Creating Analytics account
     * @return type
     */
    public function addSaveAction(\PITS\PitsSiteStatistics\Domain\Model\Statistics
         $statistics) {
        $this->view->assign('statistics', $statistics);
        $this->statisticsRepository->add($statistics);
        $arguments                            = [];
        $arguments['alertmessage']['message'] = '<i class="fa fa-check" aria-hidden="true"></i> A new account \'' . $statistics->getWebTitle() . '\' has been created succesfully.';
        $arguments['alertmessage']['type']    = 'success';
        $this->redirect('list', 'Statistics', 'PitsSiteStatistics', $arguments);
    }

    /**
     * Update Account action
     * @param \PITS\PitsSiteStatistics\Domain\Model\Statistics
     * @ignorevalidation  $statistics
     */
    public function updateSaveAction(\PITS\PitsSiteStatistics\Domain\Model\Statistics
         $statistics) {
        $arguments                            = [];
        $arguments['alertmessage']['message'] = '<i class="fa fa-check" aria-hidden="true"></i> Account \'' . $statistics->getWebTitle() . '\' updated succesfully.';
        $arguments['alertmessage']['type']    = 'success';
        $this->statisticsRepository->update($statistics);
        $this->redirect('list', 'Statistics', 'PitsSiteStatistics', $arguments);
    }

    /**
     * Deleting an account
     * @return type
     */
    public function deleteAction()
    {
        $uid                                  = $this->request->getArgument('statistics');
        $statistics                           = $this->statisticsRepository->findByUid($uid);
        $arguments                            = [];
        $arguments['alertmessage']['message'] = '<i class="fa fa-check" aria-hidden="true"></i> Account \'' . $statistics->getWebTitle() . '\' has been deleted.';
        $arguments['alertmessage']['type']    = 'success';
        $this->statisticsRepository->remove($statistics);
        $this->redirect('list', 'Statistics', 'PitsSiteStatistics', $arguments);

    }

    /**
     * Listing Analytics accounts
     * @return type
     */
    public function listAction()
    {
        if ($this->request->hasArgument('alertmessage')) {
            $alertmessage = $this->request->getArgument('alertmessage');
            $this->view->assign('alertmessage', $alertmessage);
        }

        $analyticaccounts = $this->statisticsRepository->findAll();
        $this->view->assign('accounts', $analyticaccounts);
    }

    /**
     * Getting metadata
     * @return type
     */
    public function metadata()
    {
        $metadata = $this->googleAnalytics->metadata($this->client);
        return $metadata;
    }

    /**
     * getting Metrics uinames
     * @return type array
     */
    public function getMetrics()
    {
        $allMetrics = [];
        $metadata   = $this->metadata();
        foreach ($metadata['metrics'] as $key => $value) {
            if (sizeof($value) > 1) {
                foreach ($value as $metric) {
                    $allMetrics[$key][$metric->id] = $metric->attributes->uiName;
                }
            }
        }

        return $allMetrics;
    }

    /**
     * getting Dimensions uinames
     * @return type array
     */
    public function getDimensions()
    {
        $allDimensions = [];
        $metadata      = $this->metadata();
        foreach ($metadata['dimensions'] as $key => $value) {
            if (sizeof($value) > 1) {
                foreach ($value as $dimension) {
                    $allDimensions[$key][$dimension->id] = $dimension->attributes->uiName;
                }
            }
        }
        return $allDimensions;
    }

    /**
     * Get all analytics accounts
     * @return type
     */
    public function getAccounts()
    {
        $analyticsAccounts = $this->statisticsRepository->findAll();
        return $analyticsAccounts;
    }

    /**
     * Set the analytic client
     * @return type
     */
    public function setClient($client)
    {
        $this->client = $client;
    }

    /**
     * Get the analytic client
     * @return type
     */
    public function getClient()
    {
        return $this->client;
    }

    /**
     * Set the analytic service
     * @return type
     */
    public function setAnalytics($analytics)
    {
        $this->analytics = $analytics;
    }

    /**
     * Get the analytic service
     * @return type
     */
    public function getAnalytics()
    {
        return $this->analytics;
    }

}
