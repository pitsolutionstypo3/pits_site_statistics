<?php
namespace PITS\PitsSiteStatistics\Validation\Validator;

class WebTitleValidator extends \TYPO3\CMS\Extbase\Validation\Validator
\AbstractValidator
{
    /**
     * StatisticsRepository
     *
     * @var PITS\PitsSiteStatistics\Domain\Repository\StatisticsRepository
     * @inject
     */
    protected $statisticsRepository;

    public function isValid($property)
    {
        $matchingRecord = $this->statisticsRepository->findByWebTitle($property);
        $obj_array            = $matchingRecord->toArray();
        
       if(sizeof($obj_array) > 0){
         $this->addError('Duplicate account title.Please add a unique title', 1383400017);
            return false;
        }
        else{
         return true;
        }
        
    }
}
