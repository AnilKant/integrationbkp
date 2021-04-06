<?php

/**
 * CedCommerce
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the End User License Agreement (EULA)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://cedcommerce.com/license-agreement.txt
 *
 * @category    Ced
 * @package     Ced_Walmart
 * @author 		CedCommerce Core Team <connect@cedcommerce.com>
 * @copyright   Copyright CedCommerce (http://cedcommerce.com/)
 * @license      http://cedcommerce.com/license-agreement.txt
 */

namespace frontend\components\Product;
use Yii;
use yii\base\Component;
/**
 * Class Data For Walmart Category Attributes
 * @package Ced\Walmart\Helper
 */
class Attributes extends component
{
    /**
     * Generate Array
     * @param null $name
     * @param string|[] $value
     * @return array|bool
     */
    public function generateArray($name = null, $value)
    {
        if ($name != null) {
            $arr = explode("/", $name);
            if (count($arr) == 1) {
                $arr = [
                    $arr[0] => $value,
                ];
                return $arr;
            }
            if (count($arr) == 2) {
                $arr = [$arr[0] => [
                    $arr[1] => $value,
                ]];
                return $arr;

            }
            if (count($arr) == 3) {
                $arr = [$arr[0] => [
                    $arr[1] => [
                        $arr[2] => $value,
                    ],
                ]];
                return $arr;
            }
        }
        return false;
    }

    /**
     * Insert Category Data
     * @param string|[] $product
     * @param string|[] $attributes
     * @param string|[] $category
     * @param string|[] $type
     * @return string|[]
     */
    public function setCategoryAttrData(
        $product = [],
        $attributes = [],
        $category = [],
        $type = []
    ) {
        $data = [];
        switch ($category['parent_cat_id']) {
            case 'Animal' : {
                $data = $this->setFoodAndBeverage(
                    $product,
                    $attributes,
                    $category,
                    $type
                );
                break;
            }

            case 'ArtAndCraft' : {
                $data = $this->setFoodAndBeverage(
                    $product,
                    $attributes,
                    $category,
                    $type
                );
                break;
            }
            case 'Baby' : {
                $data = $this->setFoodAndBeverage(
                    $product,
                    $attributes,
                    $category,
                    $type
                );
                break;
            }
            case 'CarriersAndAccessories' : {
                $data = $this->setFoodAndBeverage(
                    $product,
                    $attributes,
                    $category,
                    $type
                );

                break;
            }
            case 'Clothing' : {
                $data = $this->setFoodAndBeverage(
                    $product,
                    $attributes,
                    $category,
                    $type
                );

                break;
            }
            case 'Electronics' : {
                $data = $this->setFoodAndBeverage(
                    $product,
                    $attributes,
                    $category,
                    $type
                );
                break;
            }
            case 'FoodAndBeverage' : {
                $data = $this->setFoodAndBeverage(
                    $product,
                    $attributes,
                    $category,
                    $type
                );

                break;
            }
            case 'Footwear' : {
                $data = $this->setFoodAndBeverage(
                    $product,
                    $attributes,
                    $category,
                    $type
                );

                break;
            }
            case 'Furniture' : {
                $data = $this->setFoodAndBeverage(
                    $product,
                    $attributes,
                    $category,
                    $type
                );
                break;
            }

            case 'GardenAndPatio' :{
                $data = $this->setFoodAndBeverage(
                    $product,
                    $attributes,
                    $category,
                    $type
                );

                break;
            }
            case 'HealthAndBeauty' : {
                $data = $this->setFoodAndBeverage(
                    $product,
                    $attributes,
                    $category,
                    $type
                );

                break;
            }
            case 'Home' : {
                $data = $this->setFoodAndBeverage(
                    $product,
                    $attributes,
                    $category,
                    $type
                );

                break;
            }
            case 'Jewelry' : {
                $data = $this->setFoodAndBeverage(
                    $product,
                    $attributes,
                    $category,
                    $type
                );

                break;
            }
            case 'Media' : {
                $data = $this->setFoodAndBeverage(
                    $product,
                    $attributes,
                    $category,
                    $type
                );

                break;
            }
            case 'MusicalInstrument' :{
                $data = $this->setFoodAndBeverage(
                    $product,
                    $attributes,
                    $category,
                    $type
                );

                break;
            }
            case 'OccasionalAndSeasonal' : {
                $data = $this->setFoodAndBeverage(
                    $product,
                    $attributes,
                    $category,
                    $type
                );

                break;
            }
            case 'Office' : {
                $data = $this->setFoodAndBeverage(
                    $product,
                    $attributes,
                    $category,
                    $type
                );

                break;
            }
            case 'Photography' : {
                $data = $this->setFoodAndBeverage(
                    $product,
                    $attributes,
                    $category,
                    $type
                );

                break;
            }
            case 'ToolsAndHardware' : {
                $data = $this->setFoodAndBeverage(
                    $product,
                    $attributes,
                    $category,
                    $type
                );
                break;
            }
            case 'Toys' : {
                $data = $this->setFoodAndBeverage(
                    $product,
                    $attributes,
                    $category,
                    $type
                );

                break;
            }
            case 'Vehicle' : {
                $data = $this->setFoodAndBeverage(
                    $product,
                    $attributes,
                    $category,
                    $type
                );

                break;
            }
            case 'Watches' : {
                $data = $this->setFoodAndBeverage(
                    $product,
                    $attributes,
                    $category,
                    $type
                );

                break;
            }
        }
        return $data;
    }

    /**
     * Insert FoodAndBeverage Category Data
     * @param string|[] $product
     * @param string|[] $attributes
     * @param string|[] $category
     * @param string|[] $type
     * @return string|[]
     */
    public function setFoodAndBeverage(
        $product = [],
        $attributes = [],
        $category = [],
        $type = [
        'type' => 'simple',
        'variantid' => null,
        'variantattr' => null,
        'isprimary' => '0'
        ]
    ) {
        $product['blank'] = '';
        $attributes['variantGroupId'] = 'blank';
        $attributes['variantAttributeNames/variantAttributeName'] = 'blank';
        $attributes['isPrimaryVariant'] = 'blank';

        if (isset($type['type'],$type['variantid'], $type['variantattr']) && !empty($type['variantid'])) {
            $attributes['variantGroupId'] = 'variantGroupId';
            $attributes['variantAttributeNames/variantAttributeName'] = 'variantAttributeNames/variantAttributeName';
            $attributes['isPrimaryVariant'] = 'isPrimaryVariant';

            $product['variantGroupId'] = $type['variantid'];
            $product['variantAttributeNames/variantAttributeName'] = $type['variantattr'];
            $product['isPrimaryVariant'] = $type['isprimary'];
        }
        $data = [];

        if (!empty($product) && !empty($attributes) && !empty($category)) {
            $walmartAttr = [
                'isNutritionFactsLabelRequired', 'nutritionFactsLabel', 'nutritionFactsLabel',
                'foodForm', 'isImitation', 'foodAllergenStatements/foodAllergenStatement', 'usdaInspected',
                'vintage', 'timeAged/unit', 'timeAged/measure', 'variantAttributeNames/variantAttributeName',
                'isGmoFree','variantGroupId', 'isPrimaryVariant', 'isBpaFree', 'isPotentiallyHazardousFood',
                'isReadyToEat','caffeineDesignation', 'brand', 'manufacturer', 'spiceLevel', 'flavor', 'beefCut',
                'poultryCut', 'color/colorValue', 'isMadeInHomeKitchen', 'nutrientContentClaims/nutrientContentClaim',
                'safeHandlingInstructions', 'character/characterValue', 'occasion/occasionValue', 'isPersonalizable',
                'fatCaloriesPerGram', 'recommendedUses/recommendedUse', 'carbohydrateCaloriesPerGram',
                'totalProtein/unit', 'totalProtein/measure', 'totalProteinPercentageDailyValue/unit',
                'totalProteinPercentageDailyValue/measure', 'proteinCaloriesPerGram', 'isFairTrade', 'isIndustrial',
                'ingredients', 'releaseDate', 'servingSize', 'servingsPerContainer',
                'organicCertifications/organicCertification', 'instructions', 'calories', 'caloriesFromFat/unit',
                'caloriesFromFat/measure', 'totalFat/unit', 'totalFat/measure','totalFatPercentageDailyValue/unit',
                'totalFatPercentageDailyValue/measure','totalCarbohydrate/unit','totalCarbohydrate/measure',
                'totalCarbohydratePercentageDailyValue/unit','totalCarbohydratePercentageDailyValue/measure',
                'nutrients/nutrient'
            ];
            foreach ($walmartAttr as $attr) {
                if (isset($product[$attributes[$attr]]) && !empty($product[$attributes[$attr]]) ) {
                    $data = array_merge_recursive($data, $this->generateArray($attr, $product[$attributes[$attr]]));
                }
            }
            switch ($category['cat_id']) {
                case 'AlcoholicBeverages' : {
                    $data['AlcoholicBeverages'] = $this->setAlcoholicBeverages($product = [], $attributes = []);
                }
            }
        }
        return $data;
    }

    /**
     * Insert AlcoholicBeverages Category Data
     * @param string|[] $product
     * @param string|[] $attributes
     * @return string|[]
     */
    public function setAlcoholicBeverages($product = [], $attributes = [])
    {
        $walmartAttr = [
            'alcoholContentByVolume', 'alcoholProof', 'alcoholClassAndType', 'neutralSpiritsColoringAndFlavoring',
            'whiskeyPercentage', 'isEstateBottled', 'wineAppellation', 'wineVarietal', 'containsSulfites', 'isNonGrape'
        ];
        $data = [];

        if (!empty($product) && !empty($attributes)) {
            foreach ($walmartAttr as $attr) {
                if (!empty($product[$attributes[$attr]])) {
                    $data = array_merge_recursive($data, $this->generateArray($attr, $product[$attributes[$attr]]));
                }
            }
        }
        return $data;
    }
    /**
     * Insert HealthAndBeauty Category Data
     * @param string|[] $product
     * @param string|[] $attributes
     * @param string|[] $category
     * @param string|[] $type
     * @return string|[]
     */
    public function setHealthAndBeauty(
        $product = [],
        $attributes = [],
        $category = [],
        $type = [
            'type' => 'simple',
            'variantid' => null,
            'variantattr' => null,
            'isprimary' => '0'
        ]
    ) {
        $product['blank'] = '';
        if (isset($type['type'],$type['variantid'], $type['variantattr']) && !empty($type['variantid'])) {
            $product['variantGroupId'] = $type['variantid'];
            $product['variantAttributeNames/variantAttributeName'] = $type['variantattr'];
            $product['isPrimaryVariant'] = $type['isprimary'];

        }
        $data = [];

        if (!empty($product) && !empty($attributes) && !empty($category)) {
            $walmartAttr = [
                'swatchImages/swatchImage/swatchImageUrl', 'swatchImages/swatchImage/swatchVariantAttribute',
                'collection','foodForm', 'isImitation', 'foodAllergenStatements/foodAllergenStatement',
                'variantAttributeNames/variantAttributeName','flexibleSpendingAccountEligible', 'variantGroupId',
                'isPrimaryVariant', 'fabricContent/fabricContentValue', 'isAdultProduct',
                'fabricCareInstructions/fabricCareInstruction', 'brand', 'manufacturer', 'modelNumber',
                'manufacturerPartNumber',
                'gender', 'color/colorValue', 'ageGroup/ageGroupValue', 'isReusable', 'isDisposable',
                'material/materialValue', 'isPowered',
                'numberOfPieces', 'character/characterValue', 'powerType',
                'isPersonalizable', 'bodyParts/bodyPart', 'isPortable',
                'cleaningCareAndMaintenance','isSet', 'isTravelSize', 'recommendedUses', 'recommendedUses/recommendedUse',
                'shape', 'compatibleBrands/compatibleBrand'
            ];
            foreach ($walmartAttr as $attr) {
                if (isset($product[$attributes[$attr]]) && !empty($product[$attributes[$attr]]) ) {
                    $data = array_merge_recursive($data, $this->generateArray($attr, $product[$attributes[$attr]]));
                }
            }
            switch ($category['cat_id']) {
                case 'AlcoholicBeverages' : {
                    $data['AlcoholicBeverages'] = $this->setAlcoholicBeverages($product = [], $attributes = []);
                }
            }
        }
        return $data;
    }

    /**
     * Insert MedicineAndSupplements Category Data
     * @param string|[] $product
     * @param string|[] $attributes
     * @return string|[]
     */
    public function setMedicineAndSupplements($product = [], $attributes = [])
    {
        $walmartAttr = [
            'isDrugFactsLabelRequired', 'drugFactsLabel', 'isSupplementFactsLabelRequired', 'supplementFactsLabel',
            'servingSize', 'servingsPerContainer', 'activeIngredients/activeIngredient/activeIngredientName',
            'activeIngredients/activeIngredient/activeIngredientPercentage', 'inactiveIngredients/inactiveIngredient',
            'healthConcerns/healthConcern','form','organicCertifications/organicCertification','instructions','dosage',
            'stopUseIndications/stopUseIndication'
        ];
        $data = [];

        if (!empty($product) && !empty($attributes)) {
            foreach ($walmartAttr as $attr) {
                if (!empty($product[$attributes[$attr]])) {
                    $data = array_merge_recursive($data, $this->generateArray($attr, $product[$attributes[$attr]]));
                }
            }
        }
        return $data;
    }

    /**
     * Insert PersonalCare Category Data
     * @param string|[] $product
     * @param string|[] $attributes
     * @return string|[]
     */
    public function setPersonalCare($product = [], $attributes = [])
    {
        $walmartAttr = [
            'ingredientClaim/ingredientClaimValue', 'isLatexFree', 'absorbency', 'resultTime/unit',
            'resultTime/measure', 'skinCareConcern','skinType','hairType','skinTone','spfValue','isAntiAging',
            'isHypoallergenic','isOilFree','isParabenFree','isNoncomodegenic','scent','isUnscented','isVegan',
            'isWaterproof','isTinted','isSelfTanning','isDrugFactsLabelRequired','drugFactsLabel',
            'activeIngredients/activeIngredient/activeIngredientName',
            'activeIngredients/activeIngredient/activeIngredientPercentage', 'inactiveIngredients/inactiveIngredient',
            'form','organicCertifications/organicCertification','instructions',
            'stopUseIndications/stopUseIndication'
        ];
        $data = [];

        if (!empty($product) && !empty($attributes)) {
            foreach ($walmartAttr as $attr) {
                if (!empty($product[$attributes[$attr]])) {
                    $data = array_merge_recursive($data, $this->generateArray($attr, $product[$attributes[$attr]]));
                }
            }
        }
        return $data;
    }

    /**
     * Insert MedicalAids Category Data
     * @param string|[] $product
     * @param string|[] $attributes
     * @return string|[]
     */
    public function setMedicalAids($product = [], $attributes = [])
    {
        $walmartAttr = [
            'isInflatable', 'isWheeled', 'isFoldable', 'isIndustrial','diameter/unit',
            'diameter/measure', 'isAssemblyRequired','assemblyInstructions','maximumWeight/unit','maximumWeight/unit',
            'isLatexFree','isAntiAging',
            'isHypoallergenic','isOilFree','isParabenFree','isNoncomodegenic','scent','isUnscented','isVegan',
            'isWaterproof','isWaterproof','healthConcerns/healthConcern'
        ];
        $data = [];

        if (!empty($product) && !empty($attributes)) {
            foreach ($walmartAttr as $attr) {
                if (!empty($product[$attributes[$attr]])) {
                    $data = array_merge_recursive($data, $this->generateArray($attr, $product[$attributes[$attr]]));
                }
            }
        }
        return $data;
    }
    /**
     * Insert Optical Category Data
     * @param string|[] $product
     * @param string|[] $attributes
     * @return string|[]
     */
    public function setOptical($product = [], $attributes = [])
    {
        $walmartAttr = [
            'frameMaterial/frameMaterialValue', 'shape', 'eyewearFrameStyle', 'lensMaterial','eyewearFrameSize',
            'uvRating', 'isPolarized','lensTint','isScratchResistant','hasAdaptiveLenses',
            'lensType/lensTypeValue'
        ];
        $data = [];

        if (!empty($product) && !empty($attributes)) {
            foreach ($walmartAttr as $attr) {
                if (!empty($product[$attributes[$attr]])) {
                    $data = array_merge_recursive($data, $this->generateArray($attr, $product[$attributes[$attr]]));
                }
            }
        }
        return $data;
    }

    /**
     * Insert HealthAndBeautyElectronics Category Data
     * @param string|[] $product
     * @param string|[] $attributes
     * @return string|[]
     */
    public function setHealthAndBeautyElectronics($product = [], $attributes = [])
    {
        $walmartAttr = [
            'batteriesRequired', 'batterySize', 'connections/connection', 'isCordless','hasAutomaticShutoff',
            'screenSize/unit', 'screenSize/measure','displayTechnology'
        ];
        $data = [];

        if (!empty($product) && !empty($attributes)) {
            foreach ($walmartAttr as $attr) {
                if (!empty($product[$attributes[$attr]])) {
                    $data = array_merge_recursive($data, $this->generateArray($attr, $product[$attributes[$attr]]));
                }
            }
        }
        return $data;
    }

}