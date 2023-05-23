<?php

namespace Database\Seeders;

use App\Models\Authors;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class AuthorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $authors = [
            [
                'name_hy' => 'Սոնա Բաբաջանյանը ',
                'name_en' => 'Sona Babajanyan',
                'about_hy' => 'Սոնա Բաբաջանյանը (ծնվ. 1965) ավստրալաբնակ հայ նկարիչ է և գեղարվեստական գրականության թարգմանիչ:
                    Ավստրալիայում, Ռուսաստանում և Հայաստանում հրատարակված բազմաթիվ գրքերի ձևավորող է, որոնց շարքում՝
                    Քորալ Վասի՝ ավստրալացի մանուկների սիրելի և բեսթսելլեր դարձած «Բարի գիշեր, Պոսում» պատկերագիրքը (Koala Books, Scholastic, 2011):
                    «Բաց դռների օրը կամ Մուշի անհավանական արկածները» Սոնայի առաջին գիրքն է, որտեղ հանդես է գալիս ոչ միայն որպես նկարազարդող,
                    այլև որպես հեղինակ։ Նա հյուսել է մի հմայիչ պատմություն, որը պատանի ընթերցողներին կտանի զարմանալի արկածներով լի կախարդական ճամփորդության:',
                'about_en' => 'Sona Babajanyan...',
                'image' => 'images/authors/Sona.png',
            ],
            [
                'name_hy' => 'Ջեֆֆ Քիննի',
                'name_en' => 'Jeff Kinney',
                'about_hy' => 'Դենիել Ջ. Սիգելը (ծնվ. 1957) ամերիկացի մանկական հոգեթերապևտ է:
                    Բժշկական գիտությունների դոկտորի աստիճանն ստացել է Հարվարդի համալսարանում, ապա բժշկական կրթությունը շարունակել Կալիֆոռնիայի համալսարանում (UCLA)՝ մանկաբուժության և մանկական, դեռահասների և մեծահասակների հոգեթերապիայի ոլորտում:
                    Դենիել Սիգելը Կալիֆոռնիայի համալսարանի բժշկական դպրոցի հոգեթերապիայի կլինիկական պրոֆեսոր է, Կալիֆոռնիայի համալսարանի Mindful Awareness Research Center-ի հիմնադիր համատնօրեն, Mindsight ինստիտուտի գործադիր տնօրեն:
                    Բազմաթիվ մրցանակների դափնեկիր է, հեղինակել է ավելի քան 10 հայտնի գիրք, որոնք դարձել են բեսթսելլեր և արժանացել ընթերցողների ու քննադատների բարձր գնահատականին։
                    Թինա Փեյն Բրայսոնը (ծնվ. 1968) ամերիկացի մանկական հոգեթերապևտ է։ The Centre for Connection-ի գործադիր տնօրենը և The Play Strong Institute-ի հիմնադիրը բժշկական գիտությունների թեկնածուի աստիճանն ստացել է Հարավային Կալիֆոռնիայի համալսարանում:
                    Նրա հետազոտական աշխատանքը նվիրված է երեխաների դաստիարակության տեսությանը և միջանձնային նյարդակենսաբանության ոլորտին: Աշխատությունները թարգմանվել են ավելի քան 50 լեզվով: Երեխաների, դեռահասների, մեծահասակների, զույգերի, ընտանիքների հետ աշխատելու մեծ փորձ ունի:
                    Գլխավորում է երեխաների, ծնողների, մանկավարժների, կլինիկական բժիշկների համար համաժողովներ ամբողջ աշխարհում:',
                'about_en' => 'Jeff Kinney...',
                'image' => 'images/authors/Jeffi.png',
            ],
        ];

        foreach ($authors as $author) {
            DB::table('authors')->insert([
                'name_hy' => $author['name_hy'],
                'name_en' => $author['name_en'],
                'about_hy' => $author['about_hy'],
                'about_en' => $author['about_en'],
                'image' => $author['image'],
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
