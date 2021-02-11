<?php
declare(strict_types=1);

namespace App\Controller;

/**
 * AdminAnnouncements Controller
 *
 * @property \App\Model\Table\AdminAnnouncementsTable $AdminAnnouncements
 * @method \App\Model\Entity\AdminAnnouncement[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class AdminAnnouncementsController extends AppController
{
    /**
     * Index method
     *
     * @return \Cake\Http\Response|null|void Renders view
     */
    public function index()
    {
        $adminAnnouncements = $this->paginate($this->AdminAnnouncements);

        $this->set(compact('adminAnnouncements'));
    }

    /**
     * View method
     *
     * @param string|null $id Admin Announcement id.
     * @return \Cake\Http\Response|null|void Renders view
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $adminAnnouncement = $this->AdminAnnouncements->get($id, [
            'contain' => [],
        ]);

        $this->set(compact('adminAnnouncement'));
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null|void Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $adminAnnouncement = $this->AdminAnnouncements->newEmptyEntity();
        if ($this->request->is('post')) {
            $adminAnnouncement = $this->AdminAnnouncements->patchEntity($adminAnnouncement, $this->request->getData());
            if ($this->AdminAnnouncements->save($adminAnnouncement)) {
                $this->Flash->success(__('The admin announcement has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The admin announcement could not be saved. Please, try again.'));
        }
        $this->set(compact('adminAnnouncement'));
    }

    /**
     * Edit method
     *
     * @param string|null $id Admin Announcement id.
     * @return \Cake\Http\Response|null|void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $adminAnnouncement = $this->AdminAnnouncements->get($id, [
            'contain' => [],
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $adminAnnouncement = $this->AdminAnnouncements->patchEntity($adminAnnouncement, $this->request->getData());
            if ($this->AdminAnnouncements->save($adminAnnouncement)) {
                $this->Flash->success(__('The admin announcement has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The admin announcement could not be saved. Please, try again.'));
        }
        $this->set(compact('adminAnnouncement'));
    }

    /**
     * Delete method
     *
     * @param string|null $id Admin Announcement id.
     * @return \Cake\Http\Response|null|void Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $adminAnnouncement = $this->AdminAnnouncements->get($id);
        if ($this->AdminAnnouncements->delete($adminAnnouncement)) {
            $this->Flash->success(__('The admin announcement has been deleted.'));
        } else {
            $this->Flash->error(__('The admin announcement could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }
    
    function home(){
        
    }
}
