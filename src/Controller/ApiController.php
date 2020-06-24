<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\Grade;
use App\Entity\Student;
use Doctrine\ORM\EntityManager;
use OpenApi\Annotations as OA;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Nelmio\ApiDocBundle\Annotation\Model;


class ApiController extends AbstractController
{


    /**
     * Add a student.
     *
     * This call can create a student.
     *
     * @Route("/api/students", methods={"POST"}, name="api_students_create")
     * @OA\Post(
     *     path="/api/students",
     *     summary="Add a student.",
     *     @OA\RequestBody(
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 @OA\Property(property="firstName",type="string"),
     *                 @OA\Property(property="lastName",type="string"),
     *                 @OA\Property(property="birthDate",type="dateTime"),
     *                 example={"firstName": "Smith", "lastName": "Jessica", "birthDate": "1985-01-01"}
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="OK"
     *     )
     * )
     * @OA\Tag(name="Student")
     */
    public function addStudent(Request $request): JsonResponse
    {

        try {

            $request = $this->transformReceivedData($request);

            $firstName = $request->get('firstName');
            $lastName = $request->get('lastName');
            $birthDate = $request->get('birthDate');

            if (!$request || !$firstName || !$lastName || !$birthDate) {

                throw new \Exception();
            }

            /** @var  EntityManager $entityManager */
            $entityManager = $this->getDoctrine()->getManager();

            $student = new Student();
            $student->setBirthDate(new \DateTime($birthDate));
            $student->setLastName($lastName);
            $student->setFirstName($firstName);

            $entityManager->persist($student);
            $entityManager->flush();

            $data = [
                'status' => Response::HTTP_CREATED,
                'success' => "Student added successfully",
            ];

            return new JsonResponse($data, Response::HTTP_CREATED);


        } catch (\Exception $e) {

            $data = [
                'status' => Response::HTTP_UNPROCESSABLE_ENTITY,
                'errors' => "Data no valid ",
            ];

            return new JsonResponse($data, Response::HTTP_UNPROCESSABLE_ENTITY);
        }

    }

    /**
     * Edit a student.
     *
     * Edit student information (last name, first name, date of birth).
     *
     * @Route("/api/students/{id}", methods={"PATCH"}, name="api_students_update")
     * @OA\Patch(
     *     path="/api/students/{id}",
     *     summary="Edit a student.",
     *     @OA\RequestBody(
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 @OA\Property(property="firstName",type="string"),
     *                 @OA\Property(property="lastName",type="string"),
     *                 @OA\Property(property="birthDate",type="dateTime"),
     *                 example={"firstName": "Smith", "lastName": "Jessica", "birthDate": "1985-01-01"}
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="OK"
     *     )
     * )
     * @OA\Tag(name="Student")
     */
    public function updateStudent(Request $request, int $id): JsonResponse
    {

        try {
            /** @var  $entityManager */
            $entityManager = $this->getDoctrine()->getManager();

            /** @var Student $student */
            $student = $entityManager->getRepository(Student::class)->find($id);

            if (!$student) {
                $data = [
                    'status' => Response::HTTP_NOT_FOUND,
                    'errors' => "Student not found",
                ];

                return new JsonResponse($data, Response::HTTP_NOT_FOUND);
            }

            $request = $this->transformReceivedData($request);

            $firstName = $request->get('firstName');
            $lastName = $request->get('lastName');
            $birthDate = $request->get('birthDate');

            if (!$request) {
                throw new \Exception();
            }

            $firstName ? $student->setFirstName($firstName) : NULL;
            $lastName ? $student->setLastName($lastName) : NULL;
            $birthDate ? $student->setBirthDate(new \DateTime($birthDate)) : NULL;

            $entityManager->persist($student);
            $entityManager->flush();

            $data = [
                'status' => Response::HTTP_OK,
                'success' => "Student updated successfully",
            ];
            return new JsonResponse($data, Response::HTTP_OK);

        } catch (\Exception $e) {

            $data = [
                'status' => Response::HTTP_UNPROCESSABLE_ENTITY,
                'errors' => "Data no valid",
            ];
            return new JsonResponse($data, Response::HTTP_UNPROCESSABLE_ENTITY);
        }

    }

    /**
     * Delete a student.
     *
     * This call delete student.
     *
     * @Route("/api/students/{id}", methods={"DELETE"}, name="api_students_remove")
     * @OA\Response(
     *     response=200,
     *     description="Delete a student"
     * )
     * @OA\Tag(name="Student")
     */
    public function removeStudent(int $id): JsonResponse
    {
        /** @var  $entityManager */
        $entityManager = $this->getDoctrine()->getManager();

        /** @var Student $student */
        $student = $entityManager->getRepository(Student::class)->find($id);


        if (!$student) {
            $data = [
                'status' => Response::HTTP_NOT_FOUND,
                'errors' => "Student not found",
            ];
            return new JsonResponse($data, Response::HTTP_NOT_FOUND);

        }

        $entityManager->remove($student);
        $entityManager->flush();
        $data = [
            'status' => Response::HTTP_OK,
            'success' => "Student deleted successfully",
        ];
        return new JsonResponse($data, Response::HTTP_OK);

    }

    /**
     * Average of all of a student's grades.
     *
     * This call retrieve the average of all of a student's grades.
     *
     * @Route("/api/students/averages", methods={"GET"}, name="api_all_students_averages")
     * @OA\Response(
     *     response=200,
     *     description="Returns average of all of a student's grades."
     * )
     * @OA\Tag(name="Average")
     */
    public function overallClassAverage(): JsonResponse
    {
        $average = $this->getDoctrine()
            ->getRepository(Grade::class)
            ->retrieveAllAverage();

        $data = [
            'average' => $average
        ];

        return new JsonResponse($data, Response::HTTP_OK);
    }

    /**
     * Average of  a student's grades.
     *
     * This call retrieve the average of a student's grades.
     *
     * @Route("/api/students/{id}/averages", methods={"GET"}, name="api_students_average")
     * @OA\Response(
     *     response=200,
     *     description="Returns average of  a student's grades."
     * )
     * @OA\Tag(name="Average")
     */
    public function studentAverage(int $id): JsonResponse
    {
        /** @var  $entityManager */
        $entityManager = $this->getDoctrine()->getManager();

        /** @var Student $student */
        $student = $entityManager->getRepository(Student::class)->find($id);


        if (!$student) {
            $data = [
                'status' => Response::HTTP_NOT_FOUND,
                'errors' => "Student not found",
            ];

            return new JsonResponse($data, Response::HTTP_NOT_FOUND);

        }

        $average = $entityManager
            ->getRepository(Grade::class)
            ->retrieveStudentAverage($student);

        $data = [
            'average' => $average ?? 0
        ];

        return new JsonResponse($data, Response::HTTP_OK);

    }

    /**
     * Student grade.
     *
     * Add grade of student.
     *
     * @Route("/api/students/{id}/grades", methods={"POST"}, name="api_students_grades_add")
     * @OA\Post(
     *     path="/api/students/{id}/grades",
     *     summary="Add grade of student.",
     *     @OA\RequestBody(
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 @OA\Property(property="subject",type="string"),
     *                 @OA\Property(property="mark",type="float"),
     *                 example={"subject": "SQL", "mark": 15}
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="OK"
     *     )
     * )
     * @OA\Tag(name="Student Grade")
     */
    public function addStudentGrade(Request $request, int $id): JsonResponse
    {

        try {
            /** @var  $entityManager */
            $entityManager = $this->getDoctrine()->getManager();

            /** @var Student $student */
            $student = $entityManager->getRepository(Student::class)->find($id);

            if (!$student) {
                $data = [
                    'status' => Response::HTTP_NOT_FOUND,
                    'errors' => "Student not found",
                ];
                return new JsonResponse($data, Response::HTTP_NOT_FOUND);
            }

            $request = $this->transformReceivedData($request);

            $subject = $request->get('subject');
            $mark = $request->get('mark');

            if (!$request || !$subject || !$mark || $mark < 0 || $mark > 20) {
                throw new \Exception();
            }

            $grade = new Grade();
            $grade->setMark($mark);
            $grade->setSubject($subject);
            $grade->setStudent($student);

            $entityManager->persist($grade);
            $entityManager->flush();

            $data = [
                'status' => Response::HTTP_CREATED,
                'success' => "Student updated successfully",
            ];
            return new JsonResponse($data, Response::HTTP_CREATED);

        } catch (\Exception $e) {

            $data = [
                'status' => Response::HTTP_UNPROCESSABLE_ENTITY,
                'errors' => "Data no valid",
            ];
            return new JsonResponse($data, Response::HTTP_UNPROCESSABLE_ENTITY);
        }

    }

    protected function transformReceivedData(Request $request): Request
    {
        $data = json_decode($request->getContent(), true);

        if ($data === null) {
            return $request;
        }

        $request->request->replace($data);

        return $request;
    }


}