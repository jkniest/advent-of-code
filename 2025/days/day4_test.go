package days

import (
	"testing"
)

func TestDay4(t *testing.T) {
	t.Run("part 1", func(t *testing.T) {
		solution := solveDay4_1()

		t.Logf("Result = %d", solution)
	})
}
