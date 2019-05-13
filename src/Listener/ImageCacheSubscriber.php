<?php
namespace App\Listener;

use Doctrine\Common\EventSubscriber;
use Doctrine\ORM\Event\LifeCycleEventArgs;
use Doctrine\ORM\Event\PreUpdateEventArgs;
use Liip\ImagineBundle\Imagine\Cache\CacheManager;
use Vich\UploaderBundle\Templating\Helper\UploaderHelper;

class ImageCacheSubscriber implements EventSubscriber{

	/**
	 * @var CacheManager
	 */
	private $cacheManager;

	/**
	 * @var UploaderHelper
	 */
	private $helper;

	public function __construct(CacheManager $cacheManager, UploaderHelper $helper){
		$this->cacheManager = $cacheManager;
		$this->helper = $helper;

	}

	public function getSubscribedEvents(){
		return [
			'preRemove',
			'preUpdate'
		];
	}

	public function preRemove(LifeCycleEventArgs $args){
		$entity = $args->getEntity();
		if(!$entity instanceof User){
			return;
		}
		$this->cacheManager->remove($this->helper->asset($entity, 'imageFile'));

	}

	public function preUpdate(PreUpdateEventArgs $args){
		$entity = $args->getEntity();
		if(!$entity instanceof User){
			return;
		}

		if($entity->getImageFile() instanceof UploadedFile){
			$this->cacheManager->remove($this->helper->asset($entity, 'imageFile'));
		}
	}
}