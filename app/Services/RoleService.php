<?php
namespace App\Services;

use App\Repositories\Role\RoleRepositoryInterface;

class RoleService {
     protected $roleRepository;

     /**
      * Class constructor.
      */
     public function __construct(RoleRepositoryInterface $roleRepository)
     {
         $this->roleRepository = $roleRepository;
     }

     public function getAll($data)
     {
        $paginate = $data['paginate'] ?? null;
         return $this->roleRepository->getAll($paginate);
     }

     public function getRoleByName($name)
     {
         return $this->roleRepository->getRoleByName($name);
     }
}
