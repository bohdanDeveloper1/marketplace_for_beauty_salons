<?php

namespace App\Service;

class ComputedFreeHoursService
{
        private int $openTime = 9;
        private int $closeTime = 18;

        public function isSlotFree($slotStart, $slotEnd, $reserved): bool
        {
            foreach ($reserved as [$start, $end]) {
                if (!($slotEnd <= $start || $slotStart >= $end)) {
                    // Часовий проміжок не вільний
                    return false;
                }
            }
            // Часовий проміжок вільний
            return true;
        }

        // find free hours
        public function getHours($reserved, $duration): array
        {
            $freeHours = [];
            // Перебираємо всі години від $openTime до $closeTime - $duration
            for ($i = $this->openTime; $i <= $this->closeTime - $duration; $i++) {
                if ($this->isSlotFree($i, $i + $duration, $reserved)) {
                    $freeHours[] = [$i, $i + $duration];
                }
            }
            return $freeHours;
        }
}
