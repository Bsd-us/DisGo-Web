<?php
    namespace App\Document;

    use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;
    use Symfony\Component\Validator\Constraints as Assert;

    #[MongoDB\Document(collection: 'skinsdatas')]
    class Item
    {
        #[MongoDB\Id]
        protected string $id;

        #[MongoDB\Field(type: 'string')]
        #[Assert\Regex(pattern: '/^\d+(\.\d+)?,\d+(\.\d+)?$/')]
        protected string $float_range;

        #[MongoDB\Field(type: 'string')]
        protected string $name;

        #[MongoDB\Field(type: 'collection')]
        protected array $wear;

        public function getId(): string { return $this->id; }
        public function getFloatRange(): array
        {
            $temp = explode(',',$this->float_range);
            if($temp[0] > $temp[1]) {
                throw new \Exception('Invalid float range');
            }
            return $temp;
        }
        public function getName(): string { return $this->name; }
        public function getWear(): array { return $this->wear; }
    }
