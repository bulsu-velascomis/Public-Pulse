<?php

namespace App\Services\Section;

use App\Models\SectionHeader;

class SectionService
{
    public function getSections($status)
    {
        $section = SectionHeader::when($status === "inactive", function ($query) {
            $query->onlyTrashed();
        })
        ->useFilters()
        ->dynamicPaginate();

        return $section;
    }

    public function createSection($data)
    {
        return SectionHeader::create($data);
    }

    public function updateSection($id, $data)
    {
        $section = SectionHeader::find($id);

        if (!$section) {
            return null;
        }

        $section->update($data);
        return $section;
    }

    public function deleteOrRestoreSection($id)
    {
        $section = SectionHeader::withTrashed()->find($id);

        if (!$section) {
            return null;
        }
        
        return $section;
    }
}