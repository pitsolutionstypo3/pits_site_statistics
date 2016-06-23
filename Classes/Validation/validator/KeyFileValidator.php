<?php
namespace PITS\PitsSiteStatistics\Validation\Validator;

class KeyFileValidator extends \TYPO3\CMS\Extbase\Validation\Validator
\AbstractValidator
{
    protected $supportedOptions = ['extension' => 'p12'];
    public function isValid($property)
    {
        $max      = $this->supportedOptions['extension'];
        $filename = '';
        if (!file_exists(PATH_site . $property)) {
            $this->addError('The specified keyfile doesnot exists', 1383400016);
            return false;
        } else if (file_exists(PATH_site . $property)) {
            $filePathSplited = explode('.', $property);
            $extension = $filePathSplited[1];
            if ($extension==$this->supportedOptions['extension']) {
                return true;
            } else {
                $this->addError('The specified key file is not a p12  file.please provide a p12 file', 1383400016);
                return false;
            }

        }

    }
}
