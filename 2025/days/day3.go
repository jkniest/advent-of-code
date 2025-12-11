package days

import (
	"os"

	"github.com/jkniest/advent-of-code/2025/utils"
)

// Also works for part 3.1
func day3_2_solveBank(bank string, start, remaining int) int {
	if remaining == 0 {
		return 0
	}

	largest, index := 0, 0
	for i := start + 1; i <= len(bank)-remaining; i++ {
		val := int(bank[i] - '0')
		if val > largest {
			largest = val
			index = i
		}
	}

	// math.Pow would work, but requires type casting and is less performant for our
	// simple case.

	// multiplier := int(math.Pow(10, float64(remaining-1)))
	multiplier := 1
	for k := 0; k < remaining-1; k++ {
		multiplier *= 10
	}

	return (largest * multiplier) + day3_2_solveBank(bank, index, remaining-1)
}

func solveDay3_1() int {
	file, _ := os.Open("../inputs/day3.txt")
	defer file.Close()

	return utils.Sum(utils.ReadLines(file), func(line string) int {
		return day3_2_solveBank(line, -1, 2)
	})
}

func solveDay3_2() int {
	file, _ := os.Open("../inputs/day3.txt")
	defer file.Close()

	return utils.Sum(utils.ReadLines(file), func(bank string) int {
		return day3_2_solveBank(bank, -1, 12)
	})
}
