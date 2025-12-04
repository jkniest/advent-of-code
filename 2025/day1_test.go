package main

import (
	"bufio"
	"os"
	"strconv"
	"testing"

	"github.com/stretchr/testify/require"
	"golang.org/x/exp/utf8string"
)

func TestDay1(t *testing.T) {
	t.Run("part 1", func(t *testing.T) {
		// Read input
		file, err := os.Open("inputs/day1.txt")
		require.NoError(t, err)
		t.Cleanup(func() {
			err := file.Close()
			require.NoError(t, err)
		})

		scanner := bufio.NewScanner(file)

		value := 50
		wasAtZero := 0

		for scanner.Scan() {
			// Split text into direction and amount
			text := scanner.Text()
			runeString := utf8string.NewString(text)
			direction := runeString.At(0)

			amount, err := strconv.Atoi(text[1:])
			require.NoError(t, err)

			if direction == 'L' {
				value = (value - amount + 100) % 100
			} else {
				value = (value + amount) % 100
			}

			if value == 0 {
				wasAtZero++
			}
		}

		t.Logf("Result = %d", wasAtZero)
	})

	t.Run("part 2", func(t *testing.T) {
		// Read input
		file, err := os.Open("inputs/day1.txt")
		require.NoError(t, err)
		t.Cleanup(func() {
			err := file.Close()
			require.NoError(t, err)
		})

		scanner := bufio.NewScanner(file)

		value := 50
		wasAtZero := 0

		for scanner.Scan() {
			// Split text into direction and amount
			text := scanner.Text()
			runeString := utf8string.NewString(text)
			direction := runeString.At(0)

			amount, err := strconv.Atoi(text[1:])
			require.NoError(t, err)

			if direction == 'L' {
				for range amount {
					value = (value - 1 + 100) % 100
					if value == 0 {
						wasAtZero++
					}
				}
			} else {
				for range amount {
					value = (value + 1) % 100
					if value == 0 {
						wasAtZero++
					}
				}
			}

		}

		t.Logf("Result = %d", wasAtZero)
	})
}
