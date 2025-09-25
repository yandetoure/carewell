<?php declare(strict_types=1); 

namespace Database\Seeders;

use App\Models\Article;
use Illuminate\Database\Seeder;

class ArticleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $articles = [
            [
                'title' => 'Comprendre l\'hypertension artérielle',
                'photo' => 'hypertension.jpg',
                'content' => 'L\'hypertension artérielle est une maladie chronique caractérisée par une pression artérielle élevée. Elle peut entraîner de graves complications cardiovasculaires si elle n\'est pas traitée correctement.',
                'symptoms' => 'Maux de tête, vertiges, fatigue, essoufflement, palpitations',
                'advices' => 'Adopter une alimentation équilibrée, réduire le sel, faire de l\'exercice régulièrement, éviter le tabac et l\'alcool, prendre ses médicaments régulièrement',
            ],
            [
                'title' => 'Le diabète : prévention et gestion',
                'photo' => 'diabete.jpg',
                'content' => 'Le diabète est une maladie métabolique caractérisée par un taux de glucose élevé dans le sang. Il existe deux types principaux : le diabète de type 1 et le diabète de type 2.',
                'symptoms' => 'Soif excessive, urination fréquente, fatigue, perte de poids, vision floue',
                'advices' => 'Surveiller sa glycémie, adopter une alimentation équilibrée, faire de l\'exercice, éviter les sucres rapides, consulter régulièrement son médecin',
            ],
            [
                'title' => 'Asthme : vivre avec cette maladie respiratoire',
                'photo' => 'asthme.jpg',
                'content' => 'L\'asthme est une maladie inflammatoire chronique des voies respiratoires qui provoque des difficultés respiratoires et des crises d\'asthme.',
                'symptoms' => 'Essoufflement, sifflements respiratoires, toux sèche, oppression thoracique',
                'advices' => 'Éviter les allergènes, utiliser correctement les inhalateurs, surveiller les déclencheurs, avoir un plan d\'action d\'urgence',
            ],
            [
                'title' => 'Cancer du sein : dépistage et prévention',
                'photo' => 'cancer-sein.jpg',
                'content' => 'Le cancer du sein est l\'un des cancers les plus fréquents chez les femmes. Un dépistage précoce améliore considérablement les chances de guérison.',
                'symptoms' => 'Masse dans le sein, modification de la forme, écoulement du mamelon, douleur',
                'advices' => 'Auto-examen mensuel, mammographie régulière après 50 ans, mode de vie sain, éviter l\'alcool et le tabac',
            ],
            [
                'title' => 'Maladie d\'Alzheimer : comprendre et accompagner',
                'photo' => 'alzheimer.jpg',
                'content' => 'La maladie d\'Alzheimer est une maladie neurodégénérative qui affecte la mémoire et les fonctions cognitives. Elle nécessite un accompagnement adapté.',
                'symptoms' => 'Perte de mémoire, confusion, difficultés de langage, changements d\'humeur, désorientation',
                'advices' => 'Maintenir une routine, stimuler les fonctions cognitives, adapter l\'environnement, soutenir les aidants, consulter régulièrement',
            ],
            [
                'title' => 'Arthrose : préserver ses articulations',
                'photo' => 'arthrose.jpg',
                'content' => 'L\'arthrose est une maladie dégénérative des articulations qui provoque des douleurs et une raideur. Elle peut être gérée efficacement avec les bons soins.',
                'symptoms' => 'Douleurs articulaires, raideur matinale, limitation des mouvements, gonflement',
                'advices' => 'Maintenir un poids santé, exercices doux, physiothérapie, anti-inflammatoires naturels, éviter les surcharges',
            ],
            [
                'title' => 'Dépression : reconnaître et traiter',
                'photo' => 'depression.jpg',
                'content' => 'La dépression est un trouble mental courant qui affecte l\'humeur et le fonctionnement quotidien. Elle nécessite une prise en charge professionnelle.',
                'symptoms' => 'Tristesse persistante, perte d\'intérêt, fatigue, troubles du sommeil, difficultés de concentration',
                'advices' => 'Consulter un professionnel, thérapie, médicaments si nécessaire, activité physique, soutien social',
            ],
            [
                'title' => 'Maladies cardiovasculaires : prévention primaire',
                'photo' => 'cardiovasculaire.jpg',
                'content' => 'Les maladies cardiovasculaires sont la première cause de mortalité dans le monde. La prévention est essentielle pour réduire les risques.',
                'symptoms' => 'Douleur thoracique, essoufflement, palpitations, fatigue, gonflement des jambes',
                'advices' => 'Arrêter de fumer, alimentation méditerranéenne, exercice régulier, contrôler la tension et le cholestérol',
            ],
            [
                'title' => 'Maladies infectieuses : prévention et traitement',
                'photo' => 'infectieux.jpg',
                'content' => 'Les maladies infectieuses peuvent être causées par des bactéries, virus, champignons ou parasites. La prévention est la meilleure stratégie.',
                'symptoms' => 'Fièvre, fatigue, douleurs musculaires, symptômes spécifiques selon l\'infection',
                'advices' => 'Vaccination, hygiène des mains, éviter les contacts, traitement antibiotique si nécessaire',
            ],
            [
                'title' => 'Troubles du sommeil : retrouver un sommeil réparateur',
                'photo' => 'sommeil.jpg',
                'content' => 'Les troubles du sommeil affectent la qualité de vie et la santé. Il est important d\'identifier les causes et d\'adopter de bonnes habitudes.',
                'symptoms' => 'Difficultés d\'endormissement, réveils nocturnes, fatigue matinale, somnolence diurne',
                'advices' => 'Routine de coucher, environnement propice, éviter les écrans, exercice régulier, éviter la caféine',
            ],
            [
                'title' => 'Maladies auto-immunes : comprendre le système immunitaire',
                'photo' => 'auto-immune.jpg',
                'content' => 'Les maladies auto-immunes surviennent quand le système immunitaire attaque les propres tissus de l\'organisme. Elles nécessitent une prise en charge spécialisée.',
                'symptoms' => 'Fatigue, douleurs articulaires, éruptions cutanées, symptômes spécifiques selon la maladie',
                'advices' => 'Suivi médical régulier, traitement immunosuppresseur, mode de vie sain, gestion du stress',
            ],
            [
                'title' => 'Maladies digestives : préserver son système digestif',
                'photo' => 'digestif.jpg',
                'content' => 'Les maladies digestives peuvent affecter l\'estomac, les intestins, le foie et d\'autres organes. Une alimentation adaptée est cruciale.',
                'symptoms' => 'Douleurs abdominales, nausées, vomissements, diarrhée, constipation',
                'advices' => 'Alimentation équilibrée, éviter les aliments irritants, manger lentement, hydratation suffisante',
            ],
            [
                'title' => 'Maladies neurologiques : comprendre le système nerveux',
                'photo' => 'neurologique.jpg',
                'content' => 'Les maladies neurologiques affectent le cerveau, la moelle épinière et les nerfs. Elles nécessitent souvent une prise en charge multidisciplinaire.',
                'symptoms' => 'Maux de tête, vertiges, troubles de la coordination, faiblesse musculaire, changements cognitifs',
                'advices' => 'Suivi neurologique, rééducation, médicaments spécifiques, soutien psychologique',
            ],
            [
                'title' => 'Maladies endocriniennes : équilibrer ses hormones',
                'photo' => 'endocrinien.jpg',
                'content' => 'Les maladies endocriniennes affectent les glandes qui produisent les hormones. Elles peuvent avoir des conséquences sur tout l\'organisme.',
                'symptoms' => 'Fatigue, prise ou perte de poids, troubles de l\'humeur, symptômes spécifiques selon la glande',
                'advices' => 'Traitement hormonal substitutif, surveillance régulière, alimentation adaptée, exercice',
            ],
            [
                'title' => 'Maladies pédiatriques : soigner les enfants',
                'photo' => 'pediatrie.jpg',
                'content' => 'Les maladies pédiatriques nécessitent une approche spécifique adaptée à l\'âge et au développement de l\'enfant.',
                'symptoms' => 'Fièvre, irritabilité, perte d\'appétit, symptômes spécifiques selon l\'âge',
                'advices' => 'Consultation pédiatrique, vaccination, hygiène, alimentation équilibrée, surveillance de la croissance',
            ],
            [
                'title' => 'Maladies dermatologiques : prendre soin de sa peau',
                'photo' => 'dermatologie.jpg',
                'content' => 'La peau est le plus grand organe du corps. Les maladies dermatologiques nécessitent des soins spécifiques et une protection adaptée.',
                'symptoms' => 'Éruptions cutanées, démangeaisons, rougeurs, sécheresse, lésions',
                'advices' => 'Hydratation, protection solaire, éviter les irritants, traitement topique, consultation dermatologique',
            ],
            [
                'title' => 'Maladies urologiques : préserver l\'appareil urinaire',
                'photo' => 'urologie.jpg',
                'content' => 'Les maladies urologiques affectent les reins, la vessie et les voies urinaires. Une hydratation suffisante est essentielle.',
                'symptoms' => 'Douleurs urinaires, fréquence urinaire, sang dans les urines, douleurs lombaires',
                'advices' => 'Hydratation abondante, éviter la rétention, hygiène intime, consultation urologique',
            ],
            [
                'title' => 'Maladies gynécologiques : santé féminine',
                'photo' => 'gynecologie.jpg',
                'content' => 'La santé gynécologique est importante à tous les âges de la vie. Un suivi régulier permet de prévenir et traiter les problèmes.',
                'symptoms' => 'Douleurs pelviennes, irrégularités menstruelles, écoulements, symptômes spécifiques',
                'advices' => 'Consultation gynécologique régulière, dépistage, hygiène intime, contraception adaptée',
            ],
            [
                'title' => 'Maladies ophtalmologiques : préserver sa vision',
                'photo' => 'ophtalmologie.jpg',
                'content' => 'La vision est un sens précieux. Les maladies ophtalmologiques peuvent être prévenues et traitées efficacement.',
                'symptoms' => 'Vision floue, douleurs oculaires, rougeurs, larmoiement, sensibilité à la lumière',
                'advices' => 'Examen ophtalmologique régulier, protection solaire, pauses écran, alimentation riche en vitamines',
            ],
            [
                'title' => 'Maladies ORL : soins des voies aériennes',
                'photo' => 'orl.jpg',
                'content' => 'Les maladies ORL affectent l\'oreille, le nez et la gorge. Elles peuvent avoir un impact sur la qualité de vie.',
                'symptoms' => 'Douleurs d\'oreille, congestion nasale, mal de gorge, perte d\'audition',
                'advices' => 'Hygiène nasale, éviter les irritants, protection auditive, consultation ORL',
            ],
        ];

        foreach ($articles as $article) {
            Article::create($article);
        }
    }
}
