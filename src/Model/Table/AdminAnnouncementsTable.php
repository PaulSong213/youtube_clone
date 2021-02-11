<?php
declare(strict_types=1);

namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * AdminAnnouncements Model
 *
 * @method \App\Model\Entity\AdminAnnouncement newEmptyEntity()
 * @method \App\Model\Entity\AdminAnnouncement newEntity(array $data, array $options = [])
 * @method \App\Model\Entity\AdminAnnouncement[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\AdminAnnouncement get($primaryKey, $options = [])
 * @method \App\Model\Entity\AdminAnnouncement findOrCreate($search, ?callable $callback = null, $options = [])
 * @method \App\Model\Entity\AdminAnnouncement patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\AdminAnnouncement[] patchEntities(iterable $entities, array $data, array $options = [])
 * @method \App\Model\Entity\AdminAnnouncement|false save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\AdminAnnouncement saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\AdminAnnouncement[]|\Cake\Datasource\ResultSetInterface|false saveMany(iterable $entities, $options = [])
 * @method \App\Model\Entity\AdminAnnouncement[]|\Cake\Datasource\ResultSetInterface saveManyOrFail(iterable $entities, $options = [])
 * @method \App\Model\Entity\AdminAnnouncement[]|\Cake\Datasource\ResultSetInterface|false deleteMany(iterable $entities, $options = [])
 * @method \App\Model\Entity\AdminAnnouncement[]|\Cake\Datasource\ResultSetInterface deleteManyOrFail(iterable $entities, $options = [])
 */
class AdminAnnouncementsTable extends Table
{
    /**
     * Initialize method
     *
     * @param array $config The configuration for the Table.
     * @return void
     */
    public function initialize(array $config): void
    {
        parent::initialize($config);

        $this->setTable('admin_announcements');
        $this->setDisplayField('title');
        $this->setPrimaryKey('id');
    }

    /**
     * Default validation rules.
     *
     * @param \Cake\Validation\Validator $validator Validator instance.
     * @return \Cake\Validation\Validator
     */
    public function validationDefault(Validator $validator): Validator
    {
        $validator
            ->integer('id')
            ->allowEmptyString('id', null, 'create');

        $validator
            ->scalar('title')
            ->maxLength('title', 64)
            ->requirePresence('title', 'create')
            ->notEmptyString('title');

        $validator
            ->scalar('message')
            ->maxLength('message', 200)
            ->requirePresence('message', 'create')
            ->notEmptyString('message');

        $validator
            ->scalar('link')
            ->maxLength('link', 64)
            ->requirePresence('link', 'create')
            ->notEmptyString('link');

        return $validator;
    }
}
