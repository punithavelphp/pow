<?php
namespace MageArray\Testimonials\Controller\Adminhtml\Index;

use Magento\Backend\App\Action;

/**
 * Class MassDelete
 */
class MassDelete extends \Magento\Backend\App\Action
{
    /**
     * @return \Magento\Backend\Model\View\Result\Redirect
     */
    public function execute()
    {
        $testiIds = $this->getRequest()->getParam('magearray_testimonial');
        if (!is_array($testiIds) || empty($testiIds)) {
            $this->messageManager->addError(__('Please select Testimonial.'));
        } else {
            try {
                foreach ($testiIds as $testiId) {
                    $emp = $this->_objectManager->get(
                        \MageArray\Testimonials\Model\Testimonials::Class
                    )->load($testiId);
                    $emp->delete();
                }
                $this->messageManager->addSuccess(
                    __('A total of %1 record(s) have been deleted.', count($testiIds))
                );
            } catch (\Exception $e) {
                $this->messageManager->addError($e->getMessage());
            }
        }
        return $this->resultRedirectFactory->create()->setPath('testimonials/*/index');
    }
}
