<?php

namespace Database\Seeders;

use App\Models\Crop;
use App\Models\CropProfile;
use App\Models\CropSoilSuitability;
use App\Models\CropTip;
use Illuminate\Database\Seeder;

class CropSeeder extends Seeder
{
    public function run(): void
    {
        $crops = [
            [
                'crop'    => ['name' => 'Wheat', 'scientific_name' => 'Triticum aestivum', 'category' => 'cereal', 'avg_growth_days' => 240, 'icon' => 'fa-solid fa-wheat-awn'],
                'profile' => [
                    'faostat_item_code'   => '15',
                    'description'         => 'Wheat is one of the world\'s most important cereal crops, grown across temperate regions. It is a staple food source providing carbohydrates and protein.',
                    'optimal_temp_min'    => 10,
                    'optimal_temp_max'    => 24,
                    'ph_min'              => 6.0,
                    'ph_max'              => 7.5,
                    'annual_rainfall_min' => 300,
                    'annual_rainfall_max' => 900,
                    'drought_tolerance'   => 'medium',
                    'frost_tolerance'     => 'high',
                ],
                'soils' => [
                    ['soil_type' => 'loam',  'suitability' => 'ideal'],
                    ['soil_type' => 'silt',  'suitability' => 'ideal'],
                    ['soil_type' => 'clay',  'suitability' => 'suitable'],
                    ['soil_type' => 'sandy', 'suitability' => 'marginal'],
                ],
                'tips' => [
                    ['type' => 'planting',   'soil_type' => null,    'body' => 'Sow winter wheat in autumn (Sept–Nov) at 2–3 cm depth for optimal germination.'],
                    ['type' => 'irrigation', 'soil_type' => null,    'body' => 'Critical water periods are tillering and grain fill. Avoid waterlogging at all times.'],
                    ['type' => 'soil',       'soil_type' => 'clay',  'body' => 'Improve drainage before sowing on clay soils to prevent root rot during wet winters.'],
                    ['type' => 'soil',       'soil_type' => 'sandy', 'body' => 'Apply additional nitrogen on sandy soils as nutrients leach quickly. Split applications are recommended.'],
                    ['type' => 'pest',       'soil_type' => null,    'body' => 'Monitor for aphids and rust diseases from tillering onward. Rotate with non-cereal crops every 2–3 years.'],
                    ['type' => 'harvesting', 'soil_type' => null,    'body' => 'Harvest when grain moisture falls below 14%. Delayed harvest increases risk of sprouting and quality loss.'],
                ],
            ],
            [
                'crop'    => ['name' => 'Corn', 'scientific_name' => 'Zea mays', 'category' => 'cereal', 'avg_growth_days' => 110, 'icon' => 'fa-solid fa-seedling'],
                'profile' => [
                    'faostat_item_code'   => '56',
                    'description'         => 'Maize (corn) is a warm-season cereal crop widely grown for food, feed, and industrial uses. It requires warm temperatures and moderate rainfall.',
                    'optimal_temp_min'    => 18,
                    'optimal_temp_max'    => 32,
                    'ph_min'              => 5.8,
                    'ph_max'              => 7.0,
                    'annual_rainfall_min' => 500,
                    'annual_rainfall_max' => 1200,
                    'drought_tolerance'   => 'medium',
                    'frost_tolerance'     => 'low',
                ],
                'soils' => [
                    ['soil_type' => 'loam',  'suitability' => 'ideal'],
                    ['soil_type' => 'silt',  'suitability' => 'suitable'],
                    ['soil_type' => 'sandy', 'suitability' => 'suitable'],
                    ['soil_type' => 'clay',  'suitability' => 'marginal'],
                ],
                'tips' => [
                    ['type' => 'planting',   'soil_type' => null,    'body' => 'Plant after last frost when soil temperature exceeds 10 °C. Sow at 5 cm depth, 20–25 cm spacing.'],
                    ['type' => 'irrigation', 'soil_type' => null,    'body' => 'Silking and grain fill are the most water-sensitive stages. Deficit irrigation during these periods significantly reduces yield.'],
                    ['type' => 'soil',       'soil_type' => 'clay',  'body' => 'Subsoil compaction on clay can restrict root depth. Deep tillage before planting improves drainage and root penetration.'],
                    ['type' => 'soil',       'soil_type' => 'sandy', 'body' => 'Frequent light irrigation is preferred on sandy soils to prevent nutrient leaching and drought stress.'],
                    ['type' => 'pest',       'soil_type' => null,    'body' => 'European corn borer and corn earworm are key pests. Scout weekly from VT stage and apply controls at threshold.'],
                    ['type' => 'harvesting', 'soil_type' => null,    'body' => 'Harvest silage at 65–70% moisture (R4 stage). Grain corn should reach below 25% moisture before combining.'],
                ],
            ],
            [
                'crop'    => ['name' => 'Tomato', 'scientific_name' => 'Solanum lycopersicum', 'category' => 'vegetable', 'avg_growth_days' => 80, 'icon' => 'fa-solid fa-apple-whole'],
                'profile' => [
                    'faostat_item_code'   => '388',
                    'description'         => 'Tomato is the world\'s most widely grown vegetable crop, cultivated in tropical, subtropical and temperate regions. It is highly sensitive to frost.',
                    'optimal_temp_min'    => 18,
                    'optimal_temp_max'    => 27,
                    'ph_min'              => 6.0,
                    'ph_max'              => 6.8,
                    'annual_rainfall_min' => 400,
                    'annual_rainfall_max' => 800,
                    'drought_tolerance'   => 'low',
                    'frost_tolerance'     => 'low',
                ],
                'soils' => [
                    ['soil_type' => 'loam',  'suitability' => 'ideal'],
                    ['soil_type' => 'sandy', 'suitability' => 'suitable'],
                    ['soil_type' => 'silt',  'suitability' => 'suitable'],
                    ['soil_type' => 'clay',  'suitability' => 'marginal'],
                ],
                'tips' => [
                    ['type' => 'planting',   'soil_type' => null,    'body' => 'Transplant seedlings after last frost. Space 50–70 cm apart. Stake or cage plants to support growth.'],
                    ['type' => 'irrigation', 'soil_type' => null,    'body' => 'Use drip irrigation for consistent moisture. Irregular watering causes blossom end rot and fruit cracking.'],
                    ['type' => 'soil',       'soil_type' => 'clay',  'body' => 'Raise beds and add compost on clay soils to improve drainage. Tomatoes are highly susceptible to root diseases in waterlogged conditions.'],
                    ['type' => 'soil',       'soil_type' => 'sandy', 'body' => 'Enrich sandy soils with compost before planting to improve water retention and nutrient availability.'],
                    ['type' => 'pest',       'soil_type' => null,    'body' => 'Scout for early and late blight, whitefly, and spider mites. Rotate crops and remove infected plant debris promptly.'],
                    ['type' => 'harvesting', 'soil_type' => null,    'body' => 'Harvest when fruit is fully coloured but still firm. Vine-ripened tomatoes have superior flavour and Brix content.'],
                ],
            ],
            [
                'crop'    => ['name' => 'Potato', 'scientific_name' => 'Solanum tuberosum', 'category' => 'vegetable', 'avg_growth_days' => 100, 'icon' => 'fa-solid fa-carrot'],
                'profile' => [
                    'faostat_item_code'   => '116',
                    'description'         => 'Potato is a cool-season root vegetable and one of the world\'s top food crops. It thrives in temperate climates with well-drained, loose soils.',
                    'optimal_temp_min'    => 10,
                    'optimal_temp_max'    => 21,
                    'ph_min'              => 5.0,
                    'ph_max'              => 6.5,
                    'annual_rainfall_min' => 500,
                    'annual_rainfall_max' => 900,
                    'drought_tolerance'   => 'low',
                    'frost_tolerance'     => 'medium',
                ],
                'soils' => [
                    ['soil_type' => 'sandy', 'suitability' => 'ideal'],
                    ['soil_type' => 'loam',  'suitability' => 'ideal'],
                    ['soil_type' => 'silt',  'suitability' => 'suitable'],
                    ['soil_type' => 'clay',  'suitability' => 'unsuitable'],
                ],
                'tips' => [
                    ['type' => 'planting',   'soil_type' => null,    'body' => 'Plant certified seed potatoes at 10 cm depth, 30 cm apart. Earth up regularly as stems grow to prevent greening.'],
                    ['type' => 'irrigation', 'soil_type' => null,    'body' => 'Maintain consistent moisture especially during tuber initiation. Irregular irrigation causes hollow heart and misshapen tubers.'],
                    ['type' => 'soil',       'soil_type' => 'clay',  'body' => 'Potatoes perform poorly in clay — avoid or rebuild soil with substantial sandy loam amendments before planting.'],
                    ['type' => 'soil',       'soil_type' => 'sandy', 'body' => 'Sandy soils are ideal for potato shape and harvest ease. Add organic matter to maintain moisture and fertility.'],
                    ['type' => 'pest',       'soil_type' => null,    'body' => 'Late blight (Phytophthora infestans) is the main disease threat. Apply protective fungicides preventively in humid conditions.'],
                    ['type' => 'harvesting', 'soil_type' => null,    'body' => 'Harvest 2 weeks after haulm death for firm skins. Avoid harvesting in wet or very hot conditions to prevent storage losses.'],
                ],
            ],
            [
                'crop'    => ['name' => 'Grapes', 'scientific_name' => 'Vitis vinifera', 'category' => 'fruit', 'avg_growth_days' => 180, 'icon' => 'fa-solid fa-wine-bottle'],
                'profile' => [
                    'faostat_item_code'   => '560',
                    'description'         => 'Grapevine is a perennial fruit crop cultivated worldwide for fresh consumption, wine, raisins and juice. It thrives in Mediterranean-type climates.',
                    'optimal_temp_min'    => 15,
                    'optimal_temp_max'    => 30,
                    'ph_min'              => 5.5,
                    'ph_max'              => 7.0,
                    'annual_rainfall_min' => 300,
                    'annual_rainfall_max' => 700,
                    'drought_tolerance'   => 'high',
                    'frost_tolerance'     => 'medium',
                ],
                'soils' => [
                    ['soil_type' => 'loam',  'suitability' => 'ideal'],
                    ['soil_type' => 'sandy', 'suitability' => 'ideal'],
                    ['soil_type' => 'silt',  'suitability' => 'suitable'],
                    ['soil_type' => 'clay',  'suitability' => 'marginal'],
                ],
                'tips' => [
                    ['type' => 'planting',   'soil_type' => null,    'body' => 'Plant bare-root vines in late winter. Orient rows north–south for maximum sunlight exposure.'],
                    ['type' => 'irrigation', 'soil_type' => null,    'body' => 'Grapes are drought-tolerant once established. Mild water stress during berry development can concentrate sugars and flavour.'],
                    ['type' => 'soil',       'soil_type' => 'clay',  'body' => 'Ensure drainage is excellent on clay soils. Grapevines are highly susceptible to root rot (Phytophthora) in waterlogged conditions.'],
                    ['type' => 'soil',       'soil_type' => 'sandy', 'body' => 'Sandy soils reduce vigour and improve wine quality in many varieties. Supplement with controlled fertilisation.'],
                    ['type' => 'pest',       'soil_type' => null,    'body' => 'Monitor for powdery and downy mildew, botrytis, and grape berry moth. Canopy management is the first line of defence.'],
                    ['type' => 'harvesting', 'soil_type' => null,    'body' => 'Harvest based on Brix, pH, and titratable acidity — not just colour. Sample multiple blocks before deciding.'],
                ],
            ],
            [
                'crop'    => ['name' => 'Barley', 'scientific_name' => 'Hordeum vulgare', 'category' => 'cereal', 'avg_growth_days' => 90, 'icon' => 'fa-solid fa-wheat-awn-circle-exclamation'],
                'profile' => [
                    'faostat_item_code'   => '44',
                    'description'         => 'Barley is a hardy cereal crop used for animal feed, malting and human food. It has better tolerance of drought, salinity and cold than wheat.',
                    'optimal_temp_min'    => 8,
                    'optimal_temp_max'    => 22,
                    'ph_min'              => 6.0,
                    'ph_max'              => 8.0,
                    'annual_rainfall_min' => 250,
                    'annual_rainfall_max' => 750,
                    'drought_tolerance'   => 'high',
                    'frost_tolerance'     => 'high',
                ],
                'soils' => [
                    ['soil_type' => 'loam',  'suitability' => 'ideal'],
                    ['soil_type' => 'silt',  'suitability' => 'ideal'],
                    ['soil_type' => 'sandy', 'suitability' => 'suitable'],
                    ['soil_type' => 'clay',  'suitability' => 'marginal'],
                ],
                'tips' => [
                    ['type' => 'planting',   'soil_type' => null,    'body' => 'Sow spring barley early (Feb–Apr) to maximise the growing season. Target a seed rate of 300–350 seeds/m².'],
                    ['type' => 'irrigation', 'soil_type' => null,    'body' => 'Barley is more drought-tolerant than wheat but responds well to irrigation at ear emergence and grain fill.'],
                    ['type' => 'soil',       'soil_type' => 'clay',  'body' => 'Ensure good seedbed preparation on clay soils. Barley is sensitive to waterlogging at establishment.'],
                    ['type' => 'soil',       'soil_type' => 'sandy', 'body' => 'Barley performs reliably on light sandy soils where other cereals struggle. Apply N in split doses to reduce losses.'],
                    ['type' => 'pest',       'soil_type' => null,    'body' => 'Powdery mildew and net blotch are the main foliar diseases. Resistant varieties are the most cost-effective management tool.'],
                    ['type' => 'harvesting', 'soil_type' => null,    'body' => 'Harvest at 14–18% moisture. Malting barley must reach protein specifications — avoid excessive nitrogen late in the season.'],
                ],
            ],
        ];

        foreach ($crops as $entry) {
            $crop = Crop::firstOrCreate(['name' => $entry['crop']['name']], $entry['crop']);

            CropProfile::updateOrCreate(
                ['crop_id' => $crop->id],
                $entry['profile']
            );

            foreach ($entry['soils'] as $soil) {
                CropSoilSuitability::updateOrCreate(
                    ['crop_id' => $crop->id, 'soil_type' => $soil['soil_type']],
                    ['suitability' => $soil['suitability']]
                );
            }

            foreach ($entry['tips'] as $tip) {
                \App\Models\CropTip::firstOrCreate(
                    ['crop_id' => $crop->id, 'type' => $tip['type'], 'soil_type' => $tip['soil_type'], 'body' => $tip['body']]
                );
            }
        }
    }
}
