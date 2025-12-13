package days

import (
	"math"
	"os"
	"strconv"
	"strings"

	"github.com/jkniest/advent-of-code/2025/utils"
)

type day2_state struct {
	correct bool
	prev    string
}

func day2_checkNum(num int) int {
	if num < 10 {
		return 0
	}

	length := int(math.Floor(math.Log10(float64(num)) + 1))
	strI := strconv.Itoa(num)

	part1 := strI[:length/2]
	part2 := strI[length/2 : length]

	if part1 == part2 {
		return num
	}

	return 0
}

func day2_1_checkIds(idRange string) int {
	parts := strings.Split(idRange, "-")
	start, _ := strconv.Atoi(parts[0])
	end, _ := strconv.Atoi(parts[1])

	return utils.NewCollection(utils.BuildRangeSlice(start, end)).
		Sum(day2_checkNum)
}

func day2_2_checkNumRec(number int, remaining int) int {
	if number < 10 || remaining == 1 {
		return 0
	}

	numStr := strconv.Itoa(number)
	digits := len(numStr)

	remainingRange := utils.BuildRangeSlice(0, remaining-1)
	parts := utils.Map(remainingRange, func(i int, _ int) string {
		dd := digits / remaining
		return numStr[i*dd : i*dd+dd]
	})

	state := day2_state{
		correct: true,
		prev:    parts[0],
	}

	state = utils.Reduce(parts[1:], state, func(acc day2_state, curr string) day2_state {
		if !acc.correct {
			return day2_state{
				correct: false,
				prev:    curr,
			}
		}

		if curr != acc.prev {
			return day2_state{
				correct: false,
				prev:    curr,
			}
		}

		return day2_state{
			correct: true,
			prev:    curr,
		}
	})

	// If done, we are done
	if state.correct {
		return number
	}

	// Find next smallest division
	for i := remaining - 1; i > 0; i-- {
		if digits%i == 0 {
			return day2_2_checkNumRec(number, i)
		}
	}

	return 0
}

func day2_2_checkIds(idRange string) int {
	parts := strings.Split(idRange, "-")
	start, _ := strconv.Atoi(parts[0])
	end, _ := strconv.Atoi(parts[1])

	return utils.NewCollection(utils.BuildRangeSlice(start, end)).
		Sum(func(num int) int {
			return day2_2_checkNumRec(num, int(math.Floor(math.Log10(float64(num))+1)))
		})
}

func solveDay2_1() int {
	file, _ := os.Open("../inputs/day2.txt")
	defer file.Close()

	lines := utils.ReadLines(file)
	return utils.NewCollection(utils.Reduce(lines, []string{}, func(acc []string, line string) []string {
		return append(acc, strings.Split(line, ",")...)
	})).Sum(day2_1_checkIds)
}

func solveDay2_2() int {
	file, _ := os.Open("../inputs/day2.txt")
	defer file.Close()

	lines := utils.ReadLines(file)
	return utils.NewCollection(utils.Reduce(lines, []string{}, func(acc []string, line string) []string {
		return append(acc, strings.Split(line, ",")...)
	})).Sum(day2_2_checkIds)
}
