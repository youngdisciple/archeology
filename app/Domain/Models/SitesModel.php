<?php

namespace App\Domain\Models;

class SitesModel extends BaseModel
{
    public function getSites(array $filters): array
    {
        return $this->getCollection($filters, 'archeological_site', 'site_name');
    }

    public function getSitesById(int $site_id): array | false
    {
        return $this->getCollectionById($site_id, 'archeological_site', 'site_id');
    }

    public function getSiteCulture(array $filters, int $site_id): array | false
    {
        return $this->getSubCollection($site_id, 'archeological_site', 'cultures', 'culture_id');
    }

    public function getSitePeriod(array $filters, int $site_id): array | false
    {
        return $this->getSubCollection($site_id, 'archeological_site', 'historical_period', 'period_id');
    }

    public function createSite(array $sites)
    {
        return $this->insert('archeological_sites', $sites);
    }
}
