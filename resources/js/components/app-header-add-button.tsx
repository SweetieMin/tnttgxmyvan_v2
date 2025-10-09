import { Button } from "@/components/ui/button";
import Heading from "@/components/heading";
import { Plus } from "lucide-react";

interface AppHeaderAddButtonProps {
  title: string;
  description?: string;
  buttonLabel?: string;
  onButtonClick?: () => void;
  icon?: React.ReactNode;
}

export default function AppHeaderAddButton({
  title,
  description,
  buttonLabel = "Thêm mới",
  onButtonClick,
  icon = <Plus className="h-4 w-4 mr-2" />,
}: AppHeaderAddButtonProps) {
  return (
    <div className="flex flex-col sm:flex-row sm:items-center sm:justify-between mb-6 gap-4">
      <div className="w-full sm:w-auto">
        <Heading title={title} description={description} />
      </div>

      {onButtonClick && (
        <div className="w-full sm:w-auto">
          <Button
            onClick={onButtonClick}
            className="w-full sm:w-auto flex items-center justify-center gap-2"
          >
            {icon}
            {buttonLabel}
          </Button>
        </div>
      )}
    </div>
  );
}
