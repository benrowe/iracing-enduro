<?php

declare(strict_types=1);

namespace App\Services;

class BalanceMeanArray
{
    /**
     * @param array<int, int> $nums
     * @return array{array{mixed, mixed}, array<int, int>}
     */
    public function splitListWithIndexes(array $nums): array
    {
        // Sort by value while keeping indexes
        arsort($nums);
        $totalSum = array_sum($nums);
        $target = $totalSum / 2;

        $bestDiff = PHP_INT_MAX;
        $bestSubset = [];

        $indexes = array_keys($nums);
        $count = count($nums);
        // 2^count subsets
        $totalCombinations = 1 << $count;

        // Try all subsets (brute force approach)
        $bestSubset = $this->bruteForce($totalCombinations, $count, $indexes, $nums, $target, $bestDiff, $bestSubset);

        // Split lists
        $list1 = $bestSubset;
        $list2 = $nums;

        foreach (array_keys($list1) as $idx) {
            unset($list2[$idx]);
        }

        return [$list1, $list2];
    }

    /**
     * @param array<int, int> $nums
     * @param array<int, int> $indexes
     * @param mixed[] $bestSubset
     * @return array{mixed, mixed}
     */
    private function bruteForce(
        int $totalCombinations,
        int $count,
        array $indexes,
        array $nums,
        float|int $target,
        float|int $bestDiff,
        array $bestSubset
    ): array {
        for ($i = 1; $i < $totalCombinations; $i++) {
            [$subset, $subsetSum] = $this->subset($count, $i, $indexes, $nums);

            $diff = abs($target - $subsetSum);

            if ($diff >= $bestDiff) {
                continue;
            }

            $bestDiff = $diff;
            $bestSubset = $subset;
        }
        return $bestSubset;
    }

    /**
     * @param array<int, int> $indexes
     * @param array<int, int> $nums
     * @return array{mixed, int}
     */
    private function subset(int $count, int $i, array $indexes, array $nums): array
    {
        $subset = [];
        $subsetSum = 0;

        for ($j = 0; $j < $count; $j++) {
            if (!($i & (1 << $j))) {
                continue;
            }

            $idx = $indexes[$j];
            $subset[$idx] = $nums[$idx];
            $subsetSum += $nums[$idx];
        }
        return [$subset, $subsetSum];
    }
}
