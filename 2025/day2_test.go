package main

import (
	"bufio"
	"os"
	"strconv"
	"strings"
	"sync"
	"testing"

	"github.com/stretchr/testify/require"
)

func TestDay2(t *testing.T) {
	t.Run("part 1", func(t *testing.T) {
		// Read input
		file, err := os.Open("inputs/day2.txt")
		require.NoError(t, err)
		t.Cleanup(func() {
			err := file.Close()
			require.NoError(t, err)
		})

		scanner := bufio.NewScanner(file)
		scanner.Scan()

		text := scanner.Text()
		idRanges := strings.Split(text, ",")

		sum := 0
		results := make(chan int, len(idRanges))
		var wg sync.WaitGroup

		checkIds := func(idRange string) {
			parts := strings.Split(idRange, "-")
			start, err := strconv.Atoi(parts[0])
			require.NoError(t, err)

			end, err := strconv.Atoi(parts[1])
			require.NoError(t, err)

			summedIds := 0

			for i := start; i <= end; i++ {
				if i < 10 {
					continue
				}

				strI := strconv.Itoa(i)
				strLength := len(strI)

				part1 := strI[:strLength/2]
				part2 := strI[strLength/2 : strLength]

				if part1 == part2 {
					summedIds += i
				}
			}

			results <- summedIds

			wg.Done()
		}

		for _, idRange := range idRanges {
			wg.Add(1)
			go checkIds(idRange)
		}

		wg.Wait()

		for range len(idRanges) {
			sum += <-results
		}

		t.Logf("Result = %d", sum)
	})
}
