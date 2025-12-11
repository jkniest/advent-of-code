package days

import (
	"testing"
)

func TestDay3(t *testing.T) {
	t.Run("part 1", func(t *testing.T) {
		solution := solveDay3_1()

		t.Logf("Result = %d", solution)
	})

	t.Run("part 2", func(t *testing.T) {
		solution := solveDay3_2()

		t.Logf("Result = %d", solution)
	})

}
