package main

import (
	"bufio"
	"os"
	"testing"

	"github.com/stretchr/testify/require"
)

func TestDay4(t *testing.T) {
	t.Run("part 1", func(t *testing.T) {
		// Read input
		const path = "inputs/day4.txt"
		file, err := os.Open(path)
		require.NoError(t, err)

		file2, err := os.Open(path)
		require.NoError(t, err)

		file3, err := os.Open(path)
		require.NoError(t, err)

		t.Cleanup(func() {
			err := file.Close()
			err2 := file2.Close()
			err3 := file3.Close()

			require.NoError(t, err)
			require.NoError(t, err2)
			require.NoError(t, err3)
		})

		// Helper functions
		mId := func(x, y, l int) int {
			return y*l + x
		}

		cN := func(x, y, l, ll int, checked []bool) int {
			sum := 0

			// Check all eight sides of checked
			if (x > 0 && y > 0) && checked[mId(x-1, y-1, l)] { // LT
				sum++
			}

			if y > 0 && checked[mId(x, y-1, l)] { // T
				sum++
			}

			if (x < l-1 && y > 0) && checked[mId(x+1, y-1, l)] { // RT
				sum++
			}

			if x > 0 && checked[mId(x-1, y, l)] { // L
				sum++
			}

			if x < l-1 && checked[mId(x+1, y, l)] { // R
				sum++
			}

			if (x > 0 && y < ll-1) && checked[mId(x-1, y+1, l)] { // LB
				sum++
			}

			if (y < ll-1) && checked[mId(x, y+1, l)] { // B
				sum++
			}

			if (x < l-1 && y < ll-1) && checked[mId(x+1, y+1, l)] { // RB
				sum++
			}

			return sum
		}

		// Initial run - Count lines
		lScanner := bufio.NewScanner(file)
		lineCount := 0
		length := 0
		init := false

		for lScanner.Scan() {
			lineCount++

			if !init {
				length = len(lScanner.Text())
				init = true
			}
		}

		// Second run - Build neighour map
		scanner := bufio.NewScanner(file2)
		mappy := make([]bool, lineCount*length)

		y := 0

		for scanner.Scan() {
			row := scanner.Text()
			for x, c := range row {
				mappy[mId(x, y, length)] = c == '@'
			}

			y++
		}

		// Third run - Check neighbours
		sum := 0
		y = 0
		cScanner := bufio.NewScanner(file3)

		for cScanner.Scan() {
			row := cScanner.Text()

			for x, c := range row {
				if c != '@' {
					continue
				}

				if cN(x, y, length, lineCount, mappy) < 4 {
					sum++
				}
			}

			y++
		}

		t.Logf("Result = %d", sum)
	})
}
