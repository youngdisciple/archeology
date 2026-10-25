<?php

namespace App\Helpers;

class AcceptHeaderParser
{
    /**
     * parses an HTTP Accept header to determine the best supported media type
     *
     * @param string $acceptHeader  Accept header received from the client
     * @param array $supportedTypes list of media types supported by the API
     * @return ?string best matching type, or null if none matches
     */

    public static function getBestMatch(string $acceptHeader, array $supportedTypes): ?string {
        if (empty($acceptHeader)){
            return $supportedTypes[0];
        } else {
            //* separate content types in the header
           $contentTypes = explode(',', $acceptHeader);

            //* create an array to store them
           $parsedTypes = [];

           //* extract the content types and the quality factors
           foreach ($contentTypes as $contentType) {
                $contentType = trim($contentType);
                $parts = explode(';', $contentType);

                $mediaType = trim($parts[0]);
                $q = 1.0;

                if(isset($parts[1])){
                    $factors = explode("=", $parts[1]);
                    $q = (float) $factors[1];
                }

                //* assign every type with its respective quality factor
                $parsedTypes[] = [
                    "type" => $mediaType,
                    "q" => $q
                ];
           }
        }

        //* sort in descending order
        usort($parsedTypes, function ($a, $b) {
            return $b['q'] <=> $a['q'];
        });

        //* loop through the array
        foreach ($parsedTypes as $parsedType) {
            $mediaType = $parsedType['type'];
            $q = $parsedType['q'];

            //* check the quality factor, skip this iteration if the q=0
            if ($q <= 0){ continue; }

            //* check for an exact match in supportedTypes
            if (in_array($mediaType, $supportedTypes)){
                return $mediaType;
            }

            //* wildcards
            if ($mediaType === '*/*'){
                return $supportedTypes[0]; // first supported type
            } else if ($mediaType === 'application/*') {
                foreach ($supportedTypes as $supportedType) {
                    if (str_starts_with($supportedType, 'application/')) {
                        return $supportedType;
                    }
                }
            }
        }
        return null;
    }
}
