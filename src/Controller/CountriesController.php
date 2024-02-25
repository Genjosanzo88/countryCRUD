<?php

namespace App\Controller;

use App\Entity\Countries;
use App\Form\CountriesType;
use App\Repository\CountriesRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpClient\HttpClient;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class CountriesController extends AbstractController
{
    #[Route('/', name: 'app_countries_index', methods: ['GET'])]
    public function index(CountriesRepository $countriesRepository): Response
    {
        return $this->render('countries/index.html.twig', [
            'countries' => $countriesRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_countries_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $httpClient = HttpClient::create();
        $response = $httpClient->request('GET', 'https://restcountries.com/v3.1/all');
        $countriesData = $response->toArray();

        $country = new Countries();
        $form = $this->createForm(CountriesType::class, $country, [
            'countries_data' => $countriesData
        ]);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Obtener la moneda seleccionada del formulario
            $selectedCurrency = $form->get('currency')->getData();
            $country->setCurrency($selectedCurrency);

            // Obtener el flag seleccionado del formulario
            $selectedCountryName = $form->get('country')->getData();
            $selectedCountry = array_filter($countriesData, function ($countryData) use ($selectedCountryName) {
                return $countryData['name']['common'] === $selectedCountryName;
            });

            // Verificar si se encontró el país seleccionado y establecer su bandera
            if (!empty($selectedCountry)) {
                $selectedCountryData = reset($selectedCountry);
                $flag = $selectedCountryData['flags']['png'] ?? null;
                $country->setFlag($flag);
            }

            $entityManager->persist($country);
            $entityManager->flush();

            return $this->redirectToRoute('app_countries_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('countries/new.html.twig', [
            'country' => $country,
            'form' => $form,
        ]);
    }



    #[Route('/{id}', name: 'app_countries_show', methods: ['GET'])]
    public function show(Countries $country): Response
    {
        return $this->render('countries/show.html.twig', [
            'country' => $country,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_countries_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Countries $country, EntityManagerInterface $entityManager): Response
    {
        $httpClient = HttpClient::create();
        $response = $httpClient->request('GET', 'https://restcountries.com/v3.1/all');
        $countriesData = $response->toArray();

        $form = $this->createForm(CountriesType::class, $country, [
            'countries_data' => $countriesData
        ]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();
            return $this->redirectToRoute('app_countries_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('countries/edit.html.twig', [
            'country' => $country,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/{id}', name: 'app_countries_delete', methods: ['POST'])]
    public function delete(Request $request, Countries $country, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete' . $country->getId(), $request->request->get('_token'))) {
            $entityManager->remove($country);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_countries_index', [], Response::HTTP_SEE_OTHER);
    }
}
