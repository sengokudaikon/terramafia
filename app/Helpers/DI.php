<?php

namespace App\Helpers;

use App\Service\User\UserService;

/**
 * Class DI
 *
 * @package App\Helpers
 */
class DI
{
    /**
     * Возвращает сервис работы с пользователями.
     *
     * @return UserService
     */
    public static function getUserService(): UserService
    {
        return resolve(UserService::class);
    }
//
//    /**
//     * Возвращает сервис настроек.
//     *
//     * @return SettingService
//     */
//    public static function getSettingService(): SettingService
//    {
//        return resolve(SettingService::class);
//    }
//
//    /**
//     * Возвращает сервис проектов.
//     *
//     * @return ProjectService
//     */
//    public static function getProjectService(): ProjectService
//    {
//        return resolve(ProjectService::class);
//    }
//
//    /**
//     * Возвращает сервис разделов.
//     *
//     * @return PartService
//     */
//    public static function getPartService(): PartService
//    {
//        return resolve(PartService::class);
//    }
//
//    /**
//     * Возвращает сервис участников.
//     *
//     * @return MemberService
//     */
//    public static function getMemberService(): MemberService
//    {
//        return resolve(MemberService::class);
//    }
//
//    /**
//     * Возвращает сервис задач.
//     *
//     * @return TaskService
//     */
//    public static function getTaskService(): TaskService
//    {
//        return resolve(TaskService::class);
//    }
//
//    /**
//     * Возвращает сервис уведомлений.
//     *
//     * @return NotificationsService
//     */
//    public static function getNotificationsService(): NotificationsService
//    {
//        return resolve(NotificationsService::class);
//    }
//
//    /**
//     * Возвращает сервис колонок.
//     *
//     * @return ColumnService
//     */
//    public static function getColumnService(): ColumnService
//    {
//        return resolve(ColumnService::class);
//    }
//
//    /**
//     * Возвращает сервис условий работы.
//     *
//     * @return ConditionService
//     */
//    public static function getConditionService(): ConditionService
//    {
//        return resolve(ConditionService::class);
//    }
//
//    /**
//     * Возвращает сервис местоположений.
//     *
//     * @return LocationService
//     */
//    public static function getLocationService(): LocationService
//    {
//        return resolve(LocationService::class);
//    }
//
//    /**
//     * Возвращает сервис образований.
//     *
//     * @return EducationService
//     */
//    public static function getEducationService(): EducationService
//    {
//        return resolve(EducationService::class);
//    }
//
//    /**
//     * Возвращает сервис опыта работы.
//     *
//     * @return ExperienceService
//     */
//    public static function getExperienceService(): ExperienceService
//    {
//        return resolve(ExperienceService::class);
//    }
//
//    /**
//     * Возвращает сервис особенностей профиля специалиста.
//     *
//     * @return ProfileSpecialistFeatureService
//     */
//    public static function getProfileSpecialistFeatureService(): ProfileSpecialistFeatureService
//    {
//        return resolve(ProfileSpecialistFeatureService::class);
//    }
//
//    /**
//     * Возвращает сервис соц. сетей.
//     *
//     * @return SocialNetworkService
//     */
//    public static function getSocialNetworkService(): SocialNetworkService
//    {
//        return resolve(SocialNetworkService::class);
//    }
//
//    /**
//     * Возвращает сервис заявок специалистов.
//     *
//     * @return SpecialistVerificationRequestService
//     */
//    public static function getSpecialistVerificationRequestService(): SpecialistVerificationRequestService
//    {
//        return resolve(SpecialistVerificationRequestService::class);
//    }
//
//    /**
//     * Возвращает сервис проектов профиля.
//     *
//     * @return ProjectServiceInterface
//     */
//    public static function getProfileProjectService(): ProjectServiceInterface
//    {
//        return resolve(ProjectServiceInterface::class);
//    }
//
//    /**
//     * Возвращает сервис изображений проектов профиля.
//     *
//     * @return ProjectImageServiceInterface
//     */
//    public static function getProjectImageService(): ProjectImageServiceInterface
//    {
//        return resolve(ProjectImageServiceInterface::class);
//    }
}
