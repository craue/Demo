<?php

namespace A2lix\DemoTranslationBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * @ORM\Entity
 * @UniqueEntity(fields={"title"})
 * @Gedmo\TranslationEntity(class="A2lix\DemoTranslationBundle\Entity\ProductGedmoTranslation")
 */
class ProductGedmo
{
    use \A2lix\TranslationFormBundle\Util\GedmoTranslatable;

    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\Column(nullable=false, unique=true)
     * @Gedmo\Slug(fields={"title"})
     */
    protected $slug;

    /**
     * @ORM\Column(nullable=false, unique=true)
     * @Gedmo\Translatable
     * Don't validate for NotBlank directly. Will happen in the form.
     */
    protected $title;

    /**
     * @ORM\Column(type="text", nullable=true)
     * @Gedmo\Translatable
     */
    protected $description;

    /**
     * @ORM\OneToMany(
     * 	targetEntity="ProductGedmoTranslation", mappedBy="object", cascade={"all"}
     * )
     * @Assert\Valid
     */
    protected $translations;

    public function __construct()
    {
        $this->translations = new \Doctrine\Common\Collections\ArrayCollection();
    }

    public function getId()
    {
        return $this->id;
    }

    public function getSlug()
    {
        return $this->slug;
    }

    public function setTitle($title)
    {
        $this->title = $title;
        return $this;
    }

    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Explicitly get the title for a specific locale.
     * @param string $locale
     * @return string|null
     */
    public function getTitleForLocale($locale)
    {
        foreach ($this->translations as $translation) {
            if ($translation->getField() === 'title' && $translation->getLocale() === $locale) {
                return $translation->getContent();
            }
        }

        return null;
    }

    public function setDescription($description)
    {
        $this->description = $description;
        return $this;
    }

    public function getDescription()
    {
        return $this->description;
    }
}
