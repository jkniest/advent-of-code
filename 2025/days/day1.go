package days

import (
	"os"
	"strconv"

	"github.com/jkniest/advent-of-code/2025/utils"
)

type day1_1_state struct {
	position  int
	zeroCount int
}

func day1_1_nextState(current day1_1_state, command string) day1_1_state {
	direction := command[0]
	amount, _ := strconv.Atoi(command[1:])

	newPos := current.position
	if direction == 'L' {
		newPos = (newPos - amount + 100) % 100
	} else {
		newPos = (newPos + amount) % 100
	}

	newCount := current.zeroCount
	if newPos == 0 {
		newCount++
	}

	return day1_1_state{
		position:  newPos,
		zeroCount: newCount,
	}
}

func day1_2_nextState(current day1_1_state, command string) day1_1_state {
	direction := command[0]
	amount, _ := strconv.Atoi(command[1:])

	newPos := current.position
	additionalZeros := 0

	if direction == 'R' {
		// (90 + 20) % 100 = 10
		newPos = (current.position + amount) % 100

		// If we start at 90 and move 20, we reach virtual position 110.
		// Every 100 steps is a loop that passes zero.
		additionalZeros = (current.position + amount) / 100
	} else {
		offset := amount % 100
		newPos = (current.position - offset + 100) % 100

		distToFirstZero := current.position
		if current.position == 0 {
			distToFirstZero = 100
		}

		if amount >= distToFirstZero {
			remainingSteps := amount - distToFirstZero
			additionalZeros = 1 + (remainingSteps / 100)
		}
	}

	return day1_1_state{
		position:  newPos,
		zeroCount: current.zeroCount + additionalZeros,
	}
}

func solveDay1_1() int {
	file, _ := os.Open("../inputs/day1.txt")
	defer file.Close()

	lines := utils.ReadLines(file)
	initialState := day1_1_state{
		position:  50,
		zeroCount: 0,
	}

	return utils.Reduce(lines, initialState, day1_1_nextState).zeroCount
}

func solveDay1_2() int {
	file, _ := os.Open("../inputs/day1.txt")
	defer file.Close()

	lines := utils.ReadLines(file)
	initialState := day1_1_state{
		position:  50,
		zeroCount: 0,
	}

	return utils.Reduce(lines, initialState, day1_2_nextState).zeroCount
}
