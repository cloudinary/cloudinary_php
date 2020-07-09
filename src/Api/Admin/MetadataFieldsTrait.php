<?php
/**
 * This file is part of the Cloudinary PHP package.
 *
 * (c) Cloudinary
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Cloudinary\Api\Admin;

use Cloudinary\Api\ApiClient;
use Cloudinary\Api\ApiResponse;
use Cloudinary\Api\Exception\ApiError;
use Cloudinary\Api\Metadata\MetadataDataSource;
use Cloudinary\Api\Metadata\MetadataField;

/**
 * Trait MetadataFieldsTrait
 *
 * @property ApiClient $apiClient Defined in AdminApi class.
 */
trait MetadataFieldsTrait
{
    /**
     * Returns a list of all metadata field definitions.
     *
     * @see https://cloudinary.com/documentation/admin_api#get_metadata_fields
     *
     * @return ApiResponse A list containing the list of field definitions maps.
     */
    public function listMetadataFields()
    {
        return $this->apiClient->get(ApiEndPoint::METADATA_FIELDS);
    }

    /**
     * Gets a single metadata field definition by external id.
     *
     * @see https://cloudinary.com/documentation/admin_api#get_a_metadata_field_by_external_id
     *
     * @param string $fieldExternalId The id of the field to retrieve.
     *
     * @return ApiResponse Field definitions.
     */
    public function metadataFieldByFieldId($fieldExternalId)
    {
        $uri = [ApiEndPoint::METADATA_FIELDS, $fieldExternalId];

        return $this->apiClient->get($uri);
    }

    /**
     * Creates a new metadata field definition.
     *
     * @see https://cloudinary.com/documentation/admin_api#create_a_metadata_field
     *
     * @param MetadataField $field The field to add.
     *
     * @return ApiResponse A map representing the added field.
     */
    public function addMetadataField(MetadataField $field)
    {
        return $this->apiClient->postJson([ApiEndPoint::METADATA_FIELDS], $field);
    }

    /**
     * Updates a metadata field by external id.
     *
     * Updates a metadata field definition (partially, no need to pass the entire object) passed as JSON data.
     *
     * @see https://cloudinary.com/documentation/admin_api#update_a_metadata_field_by_external_id
     * @see https://cloudinary.com/documentation/admin_api#generic_structure_of_a_metadata_field
     *
     * @param string        $fieldExternalId The id of the field to update.
     * @param MetadataField $field           The field definition.
     *
     * @return ApiResponse The updated fields definition.
     *
     * @throws ApiError
     */
    public function updateMetadataField($fieldExternalId, MetadataField $field)
    {
        $uri = [ApiEndPoint::METADATA_FIELDS, $fieldExternalId];

        return $this->apiClient->putJson($uri, $field);
    }

    /**
     * Deletes a metadata field definition by external id.
     *
     * The field should no longer be considered a valid candidate for all other endpoints.
     *
     * @see https://cloudinary.com/documentation/admin_api#delete_a_metadata_field_by_external_id
     *
     * @param string $fieldExternalId The id of the field to delete.
     *
     * @return ApiResponse An array with a "message" key. "ok" value indicates a successful deletion.
     *
     * @throws ApiError
     */
    public function deleteMetadataField($fieldExternalId)
    {
        $uri = [ApiEndPoint::METADATA_FIELDS, $fieldExternalId];

        return $this->apiClient->delete($uri);
    }

    /**
     * Deletes entries in a metadata field datasource.
     *
     * Deletes (blocks) the datasource entries for a specified metadata field definition. Sets the state of the
     * entries to inactive. This is a soft delete, the entries still exist under the hood and can be activated
     * again with the restore datasource entries method.
     *
     * @see https://cloudinary.com/documentation/admin_api#delete_entries_in_a_metadata_field_datasource
     *
     * @param string $fieldExternalId   The id of the field to update.
     * @param array  $entriesExternalId The ids of all the entries to delete from the data source.
     *
     * @return ApiResponse The remaining datasource entries.
     *
     * @throws ApiError
     */
    public function deleteDatasourceEntries($fieldExternalId, array $entriesExternalId)
    {
        $uri = [ApiEndPoint::METADATA_FIELDS, $fieldExternalId, 'datasource'];

        return $this->apiClient->deleteJson($uri, ['external_ids' => $entriesExternalId]);
    }

    /**
     * Updates a metadata field datasource.
     *
     * Updates the datasource of a supported field type (currently only enum and set), passed as JSON data. The
     * update is partial: datasource entries with an existing external_id will be updated and entries with new
     * external_id’s (or without external_id’s) will be appended.
     *
     * @see https://cloudinary.com/documentation/admin_api#update_a_metadata_field_datasource
     *
     * @param string $fieldExternalId The id of the field to update.
     * @param array  $entries         A list of datasource entries. Existing entries (according to entry id) will be
     *                                updated, new entries will be added.
     *
     * @return ApiResponse The updated field definition.
     *
     * @throws ApiError
     */
    public function updateMetadataFieldDatasource($fieldExternalId, array $entries)
    {
        $uri = [ApiEndPoint::METADATA_FIELDS, $fieldExternalId, 'datasource'];

        $metadataDataSource = new MetadataDataSource($entries);

        return $this->apiClient->putJson($uri, $metadataDataSource);
    }

    /**
     * Restore entries in a metadata field datasource.
     *
     * Restores (unblocks) any previously deleted datasource entries for a specified metadata field definition.
     * Sets the state of the entries to active.
     *
     * @see https://cloudinary.com/documentation/admin_api#restore_entries_in_a_metadata_field_datasource
     *
     * @param string $fieldExternalId    The ID of the metadata field.
     * @param array  $entriesExternalIds An array of IDs of datasource entries to restore (unblock).
     *
     * @return ApiResponse
     */
    public function restoreMetadataFieldDatasource($fieldExternalId, array $entriesExternalIds)
    {
        $uri = [ApiEndPoint::METADATA_FIELDS, $fieldExternalId, 'datasource_restore'];
        $params['external_ids'] = $entriesExternalIds;

        return $this->apiClient->postJson($uri, $params);
    }
}
