
<script>
  $("#output").pivotUI(
    [
        {color: "blue", shape: "circle"},
        {color: "red", shape: "triangle"}
    ],
    {
        rows: ["color"],
        cols: ["shape"]
    }
);
</script>