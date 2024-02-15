<?php

namespace Botble\Hotel\Forms;

use Assets;
use Botble\Base\Enums\BaseStatusEnum;
use Botble\Base\Forms\FormAbstract;
use Botble\Hotel\Http\Requests\FoodRequest;
use Botble\Hotel\Models\Food;
use Botble\Hotel\Repositories\Interfaces\CurrencyInterface;
use Botble\Hotel\Repositories\Interfaces\FoodTypeInterface;

class FoodForm extends FormAbstract
{

    /**
     * @var CurrencyInterface
     */
    protected $currencyRepository;

    /**
     * @var FoodTypeInterface
     */
    protected $foodTypeRepository;

    /**
     * FoodForm constructor.
     * @param CurrencyInterface $currencyRepository
     * @param FoodTypeInterface $foodTypeRepository
     */
    public function __construct(CurrencyInterface $currencyRepository, FoodTypeInterface $foodTypeRepository)
    {
        parent::__construct();
        $this->currencyRepository = $currencyRepository;
        $this->foodTypeRepository = $foodTypeRepository;
    }

    /**
     * {@inheritDoc}
     */
    public function buildForm()
    {

        Assets::addScripts(['input-mask'])
            ->addStylesDirectly('vendor/core/plugins/hotel/css/hotel.css');

        $currencies = $this->currencyRepository->pluck('ht_currencies.title', 'ht_currencies.id');
        $foodTypes = $this->foodTypeRepository->pluck('ht_food_types.name', 'ht_food_types.id');

        $this
            ->setupModel(new Food)
            ->setValidatorClass(FoodRequest::class)
            ->withCustomFields()
            ->add('name', 'text', [
                'label'      => trans('core/base::forms.name'),
                'label_attr' => ['class' => 'control-label required'],
                'attr'       => [
                    'placeholder'  => trans('core/base::forms.name_placeholder'),
                    'data-counter' => 120,
                ],
            ])
            ->add('description', 'textarea', [
                'label'      => trans('core/base::forms.description'),
                'label_attr' => ['class' => 'control-label'],
                'attr'       => [
                    'rows'         => 4,
                    'placeholder'  => trans('core/base::forms.description_placeholder'),
                    'data-counter' => 400,
                ],
            ])
            ->add('rowOpen1', 'html', [
                'html' => '<div class="row">',
            ])
            ->add('price', 'text', [
                'label'      => trans('plugins/hotel::food.form.price'),
                'label_attr' => ['class' => 'control-label required'],
                'wrapper'    => [
                    'class' => 'form-group col-md-6',
                ],
                'attr'       => [
                    'id'          => 'price-number',
                    'placeholder' => trans('plugins/hotel::food.form.price'),
                    'class'       => 'form-control input-mask-number',
                ],
            ])
            ->add('currency_id', 'customSelect', [
                'label'      => trans('plugins/hotel::food.form.currency'),
                'label_attr' => ['class' => 'control-label'],
                'wrapper'    => [
                    'class' => 'form-group col-md-6',
                ],
                'attr'       => [
                    'class' => 'form-control select-full',
                ],
                'choices'    => $currencies,
            ])
            ->add('rowClose1', 'html', [
                'html' => '</div>',
            ])
            ->add('status', 'customSelect', [
                'label'      => trans('core/base::tables.status'),
                'label_attr' => ['class' => 'control-label required'],
                'attr'       => [
                    'class' => 'form-control select-full',
                ],
                'choices'    => BaseStatusEnum::labels(),
            ])
            ->add('food_type_id', 'customSelect', [
                'label'      => trans('plugins/hotel::food.form.food_type'),
                'label_attr' => ['class' => 'control-label required'],
                'choices'    => $foodTypes,
            ])
            ->add('image', 'mediaImage', [
                'label'      => trans('core/base::forms.image'),
                'label_attr' => ['class' => 'control-label'],
            ])
            ->setBreakFieldPoint('status');
    }
}
