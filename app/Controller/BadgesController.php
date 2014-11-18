<?php

class BadgesController extends AppController
{
	function index()
	{
		// get all badges
		$badges = $this->Badge->find('all');
		$this->set('badges', $badges);
		
		// get all organizations, which have a badge, sorted by # of badges
		$this->loadModel('Organization');
		$orgs = $this->Organization->find('all', array(
			'recursive' => 1,
			'fields' => array(
				'id',
				'name'
			),
			'joins' => array(array(
				'table' => 'badges_organizations',
				'alias' => 'BadgeOrganization',
				'type' => 'INNER',
				'conditions' => array(
						'BadgeOrganization.organization_id = Organization.id'
					)
				)),
			'group' => array(
				'Organization.id'
			),
			'order' => array(
				'count(BadgeOrganization.badge_id) DESC',
				'Organization.name ASC'
			)
		));
		$this->set('organizations', $orgs);
	}
	
	function add()
	{	
		if ($this->request->is('post')){
	        // save
	        $this->Badge->set($this->request->data);
	        
	        // validate form upload
	        if($this->Badge->validatesIconUpload()){
	        	// move the uploaded file
	        	$icon_path = $this->_iconPathFromUploadedBadge($this->request->data['Badge']);
		        if (move_uploaded_file($this->request->data['Badge']['icon']['tmp_name'], 'img/'.$icon_path)){
		        	// setup the request
		        	unset($this->request->data['Badge']['icon']);
		        	$this->request->data['Badge']['icon_path'] = $icon_path;
					
					// save the badge
					$this->Badge->create();    
			        if($this->Badge->save($this->request->data)){
			        	// redirect
			            $this->Session->setFlash('New badge added');
			            return $this->redirect('/badges');
			        }
		        }
	        }
	    }
	}
	
	function edit($badgeID = 0)
	{
		$badge = $this->Badge->findById($badgeID);
	
		if(empty($badge)){
			$this -> Session -> setFlash('Please select a badge to edit, then use the sidebar to edit the badge.');
			$this -> redirect(array(
				'controller' => 'badges',
				'action' => 'index'
			));
		}
		
		// if submit
		if($this->request->is('put')) {
			// which request was is
			if($this->request->data('submit') == 'Save'){
				// save
				$this->Badge->id = $badgeID;
				$this->Badge->set($this->request->data);
								
				// if an icon was uploaded
				$invalidNewFileUpload = false;
				if($this->request->data['Badge']['icon']['name'] != ''){
					if($this->Badge->validatesIconUpload()){
						// upload the new file
						$icon_path = $this->_iconPathFromUploadedBadge($this->request->data['Badge']);
						move_uploaded_file($this->request->data['Badge']['icon']['tmp_name'], 'img/'.$icon_path);
						
						// update the icon path
						$this->request->data['Badge']['icon_path'] = $icon_path;
						$this->Badge->set($this->request->data);
				
					} else {
						$invalidNewFileUpload = true;
					}
				}
				
				// remove unused field
				unset($this->request->data['Badge']['icon']);
				
				if(!$invalidNewFileUpload){
					// save the badge
					if($this->Badge->save()){
						// redirect
				        $this->Session->setFlash('Badge saved');
				        $this->redirect('/badges');
				        return;
			        }
		        }
	        
	        } else if($this->request->data('submit') == 'Delete'){
	        	// Delete
	        	if($this->Badge->delete($badgeID)){
	        		// redirect
				    $this->Session->setFlash('Badge saved');
				    $this->redirect('/badges');
				    return;
				    
				} else {
					$this->Session->setFlash('Error deleting badge');
				}
	        }
		}
			
		$this->request->data = $badge;
		$this->set('badge', $badge);
	}
	
	function award($badgeID = 0)
	{
		$badge = $this->Badge->findById($badgeID);
	
		if(empty($badge)){
			$this->Session->setFlash('Please select a badge to award it to an organization.');
			$this->redirect(array(
				'controller' => 'badges',
				'action' => 'index'
			));
		}
		
		if($this->request->is('post')){
			// if there is a list of awards, use that list
			if(empty($this->request->data['awarded'])){
				// saveAll with an empty array was not doing anything, so check this and manually delete
				// unreward all organizations for this badge
				$this->loadModel('BadgesOrganization');
				$this->BadgesOrganization->deleteAll(array(
					'badge_id' => $badgeID
				));
				
			} else {
				// build the structure for saving HABTM
				$saveBadge = array(
					'Badge' => array(
						'id' => $badgeID
					),
					'Organizations' => $this->request->data['awarded']
				);
				
				// save
				$this->Badge->saveAll($saveBadge);
			}
			// don't return, keep going
		}
		
		$this->loadModel('Organization');
		$orgs_awarded = $this->Organization->find('list', array(
			'recursive' => -1,
			'joins' => array(array(
				'table' => 'badges_organizations',
				'alias' => 'BadgeOrganization',
				'type' => 'INNER',
				'conditions' => array(
						'BadgeOrganization.badge_id' => $badgeID,
						'BadgeOrganization.organization_id = Organization.id'
					)
				))
		));
		$orgs_unawarded = $this->Organization->find('list', array(
			'recursive' => -1,
			'joins' => array(array(
				'table' => 'badges_organizations',
				'alias' => 'BadgeOrganization',
				'type' => 'LEFT',
				'conditions' => array(
						'BadgeOrganization.badge_id' => $badgeID,
						'BadgeOrganization.organization_id = Organization.id'
					)
				)),
			'conditions' => 'BadgeOrganization.organization_id IS NULL'
		));
		
		$this->set('badge', $badge);
		$this->set('orgs_awarded', $orgs_awarded);
		$this->set('orgs_unawarded', $orgs_unawarded);
	}
	
	private function _iconPathFromUploadedBadge($badge)
	{
		$extension = pathinfo($badge['icon']['name'], PATHINFO_EXTENSION);
		
		return 'badges/'.Inflector::slug(strtolower($badge['name'])).'.'.$extension;
	}
}
