package main

import (
	"bufio"
	"os"
	"strconv"
	"testing"

	"github.com/stretchr/testify/require"
)

func TestDay3(t *testing.T) {
	t.Run("part 1", func(t *testing.T) {
		// Read input
		file, err := os.Open("inputs/day3.txt")
		require.NoError(t, err)
		t.Cleanup(func() {
			err := file.Close()
			require.NoError(t, err)
		})

		scanner := bufio.NewScanner(file)

		sum := 0

		for scanner.Scan() {
			// Split text into direction and amount
			bank := scanner.Text()

			// Find the largest most left number
			largest, index := 0, 0
			for i := 0; i < len(bank)-1; i++ {
				val := int(rune(bank[i]) - '0')
				if val > largest {
					largest = val
					index = i
				}
			}

			secondLargest := 0
			for i := index + 1; i < len(bank); i++ {
				val := int(rune(bank[i]) - '0')
				if val > secondLargest {
					secondLargest = val
				}
			}

			combined, err := strconv.Atoi(strconv.Itoa(largest) + strconv.Itoa(secondLargest))
			require.NoError(t, err)

			sum += combined
		}

		t.Logf("Result = %d", sum)
	})

	t.Run("part 2", func(t *testing.T) {
		// Read input
		file, err := os.Open("inputs/day3.txt")
		require.NoError(t, err)
		t.Cleanup(func() {
			err := file.Close()
			require.NoError(t, err)
		})

		scanner := bufio.NewScanner(file)

		sum := 0

		for scanner.Scan() {
			// Split text into direction and amount
			bank := scanner.Text()

			// Find the largest most left number
			result := day3RecResolve(bank, -1, 12)
			combined, err := strconv.Atoi(result)
			require.NoError(t, err)

			sum += combined
		}

		t.Logf("Result = %d", sum)
	})

}

func day3RecResolve(number string, start, remaining int) string {
	if remaining == 0 {
		return ""
	}

	largest, index := 0, 0
	for i := start + 1; i < len(number)-remaining+1; i++ { // TODO: Maybe +1?
		val := int(rune(number[i]) - '0')
		if val > largest {
			largest = val
			index = i
		}
	}

	return strconv.Itoa(largest) + day3RecResolve(number, index, remaining-1)
}
