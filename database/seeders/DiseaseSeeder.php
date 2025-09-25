<?php declare(strict_types=1); 

namespace Database\Seeders;

use App\Models\Disease;
use Illuminate\Database\Seeder;

class DiseaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $diseases = [
            // Maladies cardiovasculaires
            ['name' => 'Hypertension artérielle'],
            ['name' => 'Infarctus du myocarde'],
            ['name' => 'Angine de poitrine'],
            ['name' => 'Insuffisance cardiaque'],
            ['name' => 'Arythmie cardiaque'],
            ['name' => 'Cardiomyopathie'],
            ['name' => 'Endocardite'],
            ['name' => 'Péricardite'],
            ['name' => 'Maladie coronarienne'],
            ['name' => 'Thrombose veineuse profonde'],
            
            // Maladies respiratoires
            ['name' => 'Asthme'],
            ['name' => 'Bronchite chronique'],
            ['name' => 'Emphysème pulmonaire'],
            ['name' => 'Pneumonie'],
            ['name' => 'Tuberculose'],
            ['name' => 'Fibrose pulmonaire'],
            ['name' => 'Syndrome d\'apnée du sommeil'],
            ['name' => 'Pleurésie'],
            ['name' => 'Cancer du poumon'],
            ['name' => 'Embolie pulmonaire'],
            
            // Maladies digestives
            ['name' => 'Gastrite'],
            ['name' => 'Ulcère gastroduodénal'],
            ['name' => 'Reflux gastro-œsophagien'],
            ['name' => 'Maladie de Crohn'],
            ['name' => 'Rectocolite hémorragique'],
            ['name' => 'Syndrome du côlon irritable'],
            ['name' => 'Diverticulite'],
            ['name' => 'Hépatite'],
            ['name' => 'Cirrhose hépatique'],
            ['name' => 'Cancer colorectal'],
            ['name' => 'Cancer de l\'estomac'],
            ['name' => 'Cancer du foie'],
            
            // Maladies endocriniennes
            ['name' => 'Diabète de type 1'],
            ['name' => 'Diabète de type 2'],
            ['name' => 'Hypothyroïdie'],
            ['name' => 'Hyperthyroïdie'],
            ['name' => 'Maladie de Basedow'],
            ['name' => 'Thyroïdite de Hashimoto'],
            ['name' => 'Maladie d\'Addison'],
            ['name' => 'Syndrome de Cushing'],
            ['name' => 'Acromégalie'],
            ['name' => 'Diabète insipide'],
            
            // Maladies neurologiques
            ['name' => 'Épilepsie'],
            ['name' => 'Maladie d\'Alzheimer'],
            ['name' => 'Maladie de Parkinson'],
            ['name' => 'Sclérose en plaques'],
            ['name' => 'Migraine'],
            ['name' => 'Accident vasculaire cérébral'],
            ['name' => 'Sclérose latérale amyotrophique'],
            ['name' => 'Maladie de Huntington'],
            ['name' => 'Dystonie'],
            ['name' => 'Névralgie du trijumeau'],
            ['name' => 'Syndrome de Guillain-Barré'],
            ['name' => 'Myasthénie grave'],
            
            // Maladies dermatologiques
            ['name' => 'Eczéma'],
            ['name' => 'Psoriasis'],
            ['name' => 'Acné'],
            ['name' => 'Dermatite atopique'],
            ['name' => 'Vitiligo'],
            ['name' => 'Lupus érythémateux'],
            ['name' => 'Urticaire'],
            ['name' => 'Rosacée'],
            ['name' => 'Cancer de la peau'],
            ['name' => 'Mélanome'],
            ['name' => 'Carcinome basocellulaire'],
            ['name' => 'Carcinome spinocellulaire'],
            
            // Maladies rhumatologiques
            ['name' => 'Polyarthrite rhumatoïde'],
            ['name' => 'Arthrose'],
            ['name' => 'Spondylarthrite ankylosante'],
            ['name' => 'Lupus érythémateux systémique'],
            ['name' => 'Sclérodermie'],
            ['name' => 'Syndrome de Sjögren'],
            ['name' => 'Polymyosite'],
            ['name' => 'Dermatomyosite'],
            ['name' => 'Fibromyalgie'],
            ['name' => 'Goutte'],
            ['name' => 'Ostéoporose'],
            ['name' => 'Maladie de Paget'],
            
            // Maladies urologiques
            ['name' => 'Infections urinaires'],
            ['name' => 'Calculs rénaux'],
            ['name' => 'Insuffisance rénale'],
            ['name' => 'Glomérulonéphrite'],
            ['name' => 'Pyélonéphrite'],
            ['name' => 'Hypertrophie bénigne de la prostate'],
            ['name' => 'Cancer de la prostate'],
            ['name' => 'Cancer de la vessie'],
            ['name' => 'Cancer du rein'],
            ['name' => 'Incontinence urinaire'],
            ['name' => 'Dysfonction érectile'],
            ['name' => 'Infertilité masculine'],
            
            // Maladies gynécologiques
            ['name' => 'Endométriose'],
            ['name' => 'Fibromes utérins'],
            ['name' => 'Syndrome des ovaires polykystiques'],
            ['name' => 'Cancer du col de l\'utérus'],
            ['name' => 'Cancer de l\'ovaire'],
            ['name' => 'Cancer de l\'endomètre'],
            ['name' => 'Cancer du sein'],
            ['name' => 'Mastopathie'],
            ['name' => 'Infertilité féminine'],
            ['name' => 'Ménopause précoce'],
            ['name' => 'Dysfonction ovulatoire'],
            ['name' => 'Fausses couches récurrentes'],
            
            // Maladies pédiatriques
            ['name' => 'Asthme infantile'],
            ['name' => 'Diabète juvénile'],
            ['name' => 'Épilepsie infantile'],
            ['name' => 'Troubles du spectre autistique'],
            ['name' => 'Trouble déficitaire de l\'attention'],
            ['name' => 'Maladie cœliaque'],
            ['name' => 'Fibrose kystique'],
            ['name' => 'Drépanocytose'],
            ['name' => 'Thalassémie'],
            ['name' => 'Maladie de Kawasaki'],
            ['name' => 'Syndrome de Down'],
            ['name' => 'Malformations congénitales'],
            
            // Maladies infectieuses
            ['name' => 'VIH/SIDA'],
            ['name' => 'Hépatite A'],
            ['name' => 'Hépatite B'],
            ['name' => 'Hépatite C'],
            ['name' => 'Malaria'],
            ['name' => 'Dengue'],
            ['name' => 'Chikungunya'],
            ['name' => 'Fièvre jaune'],
            ['name' => 'Méningite'],
            ['name' => 'Encéphalite'],
            ['name' => 'Sepsis'],
            ['name' => 'Endocardite infectieuse'],
            
            // Maladies psychiatriques
            ['name' => 'Dépression'],
            ['name' => 'Trouble bipolaire'],
            ['name' => 'Schizophrénie'],
            ['name' => 'Trouble anxieux généralisé'],
            ['name' => 'Trouble panique'],
            ['name' => 'Trouble obsessionnel-compulsif'],
            ['name' => 'Trouble de stress post-traumatique'],
            ['name' => 'Anorexie mentale'],
            ['name' => 'Boulimie'],
            ['name' => 'Trouble de la personnalité borderline'],
            ['name' => 'Trouble de la personnalité antisociale'],
            ['name' => 'Dépendance aux substances'],
            
            // Maladies ophtalmologiques
            ['name' => 'Glaucome'],
            ['name' => 'Cataracte'],
            ['name' => 'Dégénérescence maculaire'],
            ['name' => 'Rétinopathie diabétique'],
            ['name' => 'Décollement de rétine'],
            ['name' => 'Uvéite'],
            ['name' => 'Kératite'],
            ['name' => 'Conjonctivite'],
            ['name' => 'Strabisme'],
            ['name' => 'Amblyopie'],
            ['name' => 'Cancer de l\'œil'],
            ['name' => 'Rétinite pigmentaire'],
            
            // Maladies ORL
            ['name' => 'Otite moyenne'],
            ['name' => 'Sinusite'],
            ['name' => 'Rhinite allergique'],
            ['name' => 'Apnée du sommeil'],
            ['name' => 'Perte d\'audition'],
            ['name' => 'Acouphènes'],
            ['name' => 'Vertiges'],
            ['name' => 'Cancer du larynx'],
            ['name' => 'Cancer de la gorge'],
            ['name' => 'Cancer des sinus'],
            ['name' => 'Polypes nasaux'],
            ['name' => 'Déviation de la cloison nasale'],
            
            // Maladies auto-immunes
            ['name' => 'Maladie de Graves'],
            ['name' => 'Thyroïdite de Hashimoto'],
            ['name' => 'Maladie de Crohn'],
            ['name' => 'Rectocolite hémorragique'],
            ['name' => 'Maladie cœliaque'],
            ['name' => 'Sclérose en plaques'],
            ['name' => 'Myasthénie grave'],
            ['name' => 'Syndrome de Guillain-Barré'],
            ['name' => 'Vascularite'],
            ['name' => 'Maladie de Behçet'],
            ['name' => 'Sarcoïdose'],
            ['name' => 'Maladie de Wegener'],
            
            // Maladies génétiques
            ['name' => 'Syndrome de Down'],
            ['name' => 'Fibrose kystique'],
            ['name' => 'Drépanocytose'],
            ['name' => 'Thalassémie'],
            ['name' => 'Maladie de Huntington'],
            ['name' => 'Maladie de Tay-Sachs'],
            ['name' => 'Hémophilie'],
            ['name' => 'Maladie de Gaucher'],
            ['name' => 'Maladie de Niemann-Pick'],
            ['name' => 'Syndrome de Marfan'],
            ['name' => 'Syndrome de Turner'],
            ['name' => 'Syndrome de Klinefelter'],
            
            // Maladies métaboliques
            ['name' => 'Diabète'],
            ['name' => 'Obésité'],
            ['name' => 'Syndrome métabolique'],
            ['name' => 'Hypercholestérolémie'],
            ['name' => 'Hypertriglycéridémie'],
            ['name' => 'Goutte'],
            ['name' => 'Maladie de Wilson'],
            ['name' => 'Hémochromatose'],
            ['name' => 'Maladie de Fabry'],
            ['name' => 'Maladie de Pompe'],
            ['name' => 'Maladie de Niemann-Pick'],
            ['name' => 'Maladie de Gaucher'],
        ];

        foreach ($diseases as $disease) {
            Disease::create($disease);
        }
    }
}
